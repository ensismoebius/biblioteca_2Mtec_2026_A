#!/usr/bin/env python3
"""Exige que a descrição do PR referencie uma issue com uma palavra-chave de
fechamento automático do GitHub (Closes/Fixes/Resolves #N) — assim, quando o
PR é mesclado, o próprio GitHub fecha a issue automaticamente (se ela ainda
estiver aberta). Esta issue precisa existir no repositório e não pode ser,
ela mesma, um Pull Request.

Issues já fechadas só são aceitas se tiverem a label "collective" — usada
para tarefas que o time inteiro resolve em paralelo, cada um com seu
próprio PR (ex.: a issue de onboarding "adicione seu nome ao README"),
onde o primeiro PR mesclado já fecha a issue e isso não deve bloquear os
demais. Para qualquer outra issue, uma vez fechada ela não pode mais
"receber" um PR novo — isso normalmente indica um PR referenciando uma
issue errada, ou trabalho duplicado.
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

COMENTARIO_HTML = re.compile(r"<!--.*?-->", re.DOTALL)


def remover_comentarios_html(corpo: str) -> str:
    """O template de PR traz instruções dentro de comentários HTML
    (<!-- ... -->), incluindo exemplos como 'Closes #12' — sem remover
    isso antes de procurar por referências, um PR com a descrição vazia
    (template intocado) passa no check por engano, "fechando" as issues
    de exemplo do próprio template."""
    return COMENTARIO_HTML.sub("", corpo or "")


def extrair_referencias(corpo: str) -> list[int]:
    return sorted({int(n) for n in PADRAO.findall(corpo or "")})


def extrair_referencias_proximas(corpo: str) -> list[tuple[str, int]]:
    """Encontra citações como 'Refs #77' que quase acertaram, para apontar
    especificamente no erro qual palavra trocar."""
    return [(m.group(0).strip(), int(m.group(1))) for m in PADRAO_PROXIMO.finditer(corpo or "")]


LABEL_COLETIVA = "collective"


def classificar_issue(repo: str, numero: int) -> str:
    """Retorna 'aberta', 'fechada_coletiva', 'fechada' ou 'inexistente'."""
    r = subprocess.run(
        ["gh", "api", f"repos/{repo}/issues/{numero}"],
        capture_output=True, text=True,
    )
    if r.returncode != 0:
        return "inexistente"
    dado = json.loads(r.stdout)
    if "pull_request" in dado:
        return "inexistente"
    if dado.get("state") == "open":
        return "aberta"
    labels = {l["name"] for l in dado.get("labels", [])}
    return "fechada_coletiva" if LABEL_COLETIVA in labels else "fechada"


def escrever_artefato_comentario(pr_number: str, corpo: str) -> None:
    """Grava o corpo do comentário e o número do PR em arquivos, para que o
    workflow 'Comentar resultados dos checks no PR' (acionado via
    workflow_run, que roda com permissão de escrita mesmo para PRs de fork)
    os publique depois. PRs de fork recebem um GITHUB_TOKEN somente leitura
    em workflows disparados por pull_request — por isso nunca tentamos
    comentar diretamente aqui."""
    with open("/tmp/comentario.md", "w", encoding="utf-8") as f:
        f.write(corpo)
    with open("/tmp/pr_number.txt", "w", encoding="utf-8") as f:
        f.write(pr_number)


def main() -> None:
    repo = os.environ["GITHUB_REPOSITORY"]
    pr_number = os.environ["PR_NUMBER"]
    corpo_pr = remover_comentarios_html(os.environ.get("PR_BODY", ""))

    referencias = extrair_referencias(corpo_pr)

    validas, fechadas, invalidas = [], [], []
    for numero in referencias:
        status = classificar_issue(repo, numero)
        if status in ("aberta", "fechada_coletiva"):
            validas.append(numero)
        elif status == "fechada":
            fechadas.append(numero)
        else:
            invalidas.append(numero)

    if validas:
        corpo = (
            f"{MARCADOR}\n"
            "## ✅ Issue vinculada corretamente\n\n"
            f"Este PR referencia: {', '.join(f'#{n}' for n in validas)} "
            "(fecha automaticamente ao ser mesclado, se ainda estiver aberta)."
        )
        avisos = []
        if fechadas:
            avisos.append(
                "já estão **fechadas** e não têm a label `collective` (não entram na "
                "contagem): " + ", ".join(f"#{n}" for n in fechadas)
            )
        if invalidas:
            avisos.append("não encontradas: " + ", ".join(f"#{n}" for n in invalidas))
        if avisos:
            corpo += "\n\n⚠️ Outras referências no texto foram ignoradas — " + "; ".join(avisos) + "."
        print("ISSUE VINCULADA — APROVADO")
        print(corpo)
        with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
            f.write(corpo + "\n")
        escrever_artefato_comentario(pr_number, corpo)
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
    if fechadas:
        linhas_erro.append(
            "\n🔒 "
            + ", ".join(f"#{n}" for n in fechadas)
            + (" já estão fechadas" if len(fechadas) > 1 else " já está fechada")
            + " e não tem a label `collective` — não pode mais receber PRs novos. "
              "Confira se você referenciou o número certo, ou se sua tarefa já foi feita "
              "por outra pessoa."
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
    escrever_artefato_comentario(pr_number, corpo)
    sys.exit(1)


if __name__ == "__main__":
    main()
