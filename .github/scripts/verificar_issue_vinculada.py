#!/usr/bin/env python3
"""Exige que a descrição do PR referencie uma issue com uma palavra-chave de
fechamento automático do GitHub (Closes/Fixes/Resolves #N) — assim, quando o
PR é mesclado, o próprio GitHub fecha a issue automaticamente (se ela ainda
estiver aberta). Esta issue precisa existir no repositório e não pode ser,
ela mesma, um Pull Request. Issues já fechadas também são aceitas: várias
pessoas podem legitimamente referenciar a mesma issue compartilhada (ex.:
a issue de onboarding "adicione seu nome ao README"), e a primeira PR
mesclada já a fecha — isso não deve bloquear as demais.
"""
import json
import os
import re
import subprocess
import sys

MARCADOR = "<!-- issue-link-bot -->"
PALAVRAS = r"(?:close[sd]?|fix(?:e[sd])?|resolve[sd]?)"
PADRAO = re.compile(rf"\b{PALAVRAS}\s*:?\s*#(\d+)", re.IGNORECASE)

# Palavras comuns que citam uma issue mas NÃO a fecham automaticamente —
# usadas só para dar uma dica específica de "quase acertou" no erro,
# apontando exatamente o que trocar (ex.: "Refs #77" -> "Closes #77").
PALAVRAS_PROXIMAS = r"(?:refs?|see|relacionad[ao]s?|relates?|ref\.?|veja)"
PADRAO_PROXIMO = re.compile(rf"\b{PALAVRAS_PROXIMAS}\s*:?\s*#(\d+)", re.IGNORECASE)


def extrair_referencias(corpo: str) -> list[int]:
    return sorted({int(n) for n in PADRAO.findall(corpo or "")})


def extrair_referencias_proximas(corpo: str) -> list[tuple[str, int]]:
    """Encontra citações como 'Refs #77' que quase acertaram, para apontar
    especificamente no erro qual palavra trocar."""
    return [(m.group(0).strip(), int(m.group(1))) for m in PADRAO_PROXIMO.finditer(corpo or "")]


def issue_existe(repo: str, numero: int) -> bool:
    r = subprocess.run(
        ["gh", "api", f"repos/{repo}/issues/{numero}"],
        capture_output=True, text=True,
    )
    if r.returncode != 0:
        return False
    dado = json.loads(r.stdout)
    return "pull_request" not in dado


def comentar(repo: str, pr_number: str, corpo: str) -> None:
    """Tenta publicar/atualizar o comentário no PR. Em PRs de fork o token é
    somente leitura e isso falha com 403 — é esperado, não um erro real, e
    nunca deve aparecer no console como se o script tivesse quebrado (por
    isso capture_output=True em toda chamada `gh`). O veredito de verdade
    já foi escrito no Step Summary do job antes desta função ser chamada."""
    lista = subprocess.run(
        ["gh", "api", f"repos/{repo}/issues/{pr_number}/comments", "--paginate"],
        capture_output=True, text=True,
    )
    comentarios = json.loads(lista.stdout or "[]") if lista.returncode == 0 else []
    existente = next((c for c in comentarios if MARCADOR in c.get("body", "")), None)
    payload = json.dumps({"body": corpo})
    if existente:
        r = subprocess.run(
            ["gh", "api", f"repos/{repo}/issues/comments/{existente['id']}", "-X", "PATCH", "--input", "-"],
            input=payload, text=True, capture_output=True,
        )
    else:
        r = subprocess.run(
            ["gh", "api", f"repos/{repo}/issues/{pr_number}/comments", "-X", "POST", "--input", "-"],
            input=payload, text=True, capture_output=True,
        )
    if r.returncode != 0:
        print(
            "Nota: não foi possível comentar no PR (normal em PRs de fork, cujo token é "
            "somente leitura). O resultado real está no Step Summary deste job, acima. ▲"
        )


def main() -> None:
    repo = os.environ["GITHUB_REPOSITORY"]
    pr_number = os.environ["PR_NUMBER"]
    corpo_pr = os.environ.get("PR_BODY", "")

    referencias = extrair_referencias(corpo_pr)

    validas, invalidas = [], []
    for numero in referencias:
        if issue_existe(repo, numero):
            validas.append(numero)
        else:
            invalidas.append(numero)

    if validas:
        corpo = (
            f"{MARCADOR}\n"
            "## ✅ Issue vinculada corretamente\n\n"
            f"Este PR referencia: {', '.join(f'#{n}' for n in validas)} "
            "(fecha automaticamente ao ser mesclado, se ainda estiver aberta)."
        )
        if invalidas:
            corpo += (
                "\n\n⚠️ Outras referências no texto foram ignoradas (não encontradas: "
                + ", ".join(f"#{n}" for n in invalidas) + ")."
            )
        print("ISSUE VINCULADA — APROVADO")
        print(corpo)
        with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
            f.write(corpo + "\n")
        comentar(repo, pr_number, corpo)
        sys.exit(0)

    linhas_erro = [
        "Nenhuma referência válida a uma issue foi encontrada na descrição do PR.",
        "",
        "Adicione uma linha como `Closes #12`, `Fixes #7` ou `Resolves #23` "
        "(em português ou inglês, `Closes`/`Fecha` não importa — use exatamente "
        "uma destas palavras: close/closes/closed, fix/fixes/fixed, resolve/resolves/resolved).",
    ]
    proximas = extrair_referencias_proximas(corpo_pr)
    if proximas:
        sugestoes = ", ".join(f"`{texto}` → troque para `Closes #{numero}`" for texto, numero in proximas)
        linhas_erro.append(
            f"\n💡 Encontramos isto na descrição: {sugestoes}. "
            "Essas palavras citam a issue mas não fecham ela automaticamente — troque pela "
            "palavra-chave certa (Closes/Fixes/Resolves) se for esse o objetivo."
        )
    if invalidas:
        linhas_erro.append(f"\nNúmeros citados que não existem como issue: {', '.join(f'#{n}' for n in invalidas)}.")

    corpo = (
        f"{MARCADOR}\n"
        "## ❌ Nenhuma issue vinculada\n\n"
        + "\n".join(linhas_erro)
        + "\n\n---\n*Edite a descrição do PR e o check reavalia automaticamente.*"
    )
    print("ISSUE VINCULADA — REPROVADO")
    print("\n".join(linhas_erro))
    with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
        f.write(corpo + "\n")
    comentar(repo, pr_number, corpo)
    sys.exit(1)


if __name__ == "__main__":
    main()
