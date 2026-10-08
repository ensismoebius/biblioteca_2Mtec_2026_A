#!/usr/bin/env python3
"""Roda `code-intelligence diff` (base do PR vs. HEAD) e comenta o resultado no PR.

O código-fonte da ferramenta vive em `.github/code_intelligence/` (cópia
vendorizada, somente leitura — nunca edite esses arquivos aqui). Sai com
código 2 se o PR introduz qualquer violação nova em relação à `main`
(qualquer severidade — a política do professor é tolerância zero), senão 0.

IMPORTANTE: o subcomando `diff` da ferramenta filtra os arquivos
analisados só pela extensão (`Workspace._is_analyzable`) — ele NUNCA
aplica o `exclude_globs` do `.code-intelligence.json` (isso só é
respeitado pelo caminho de indexação completa, não pelo `diff`). Sem
correção, isso reporta violações em arquivos que explicitamente
excluímos (migrations, blade, etc.). Por isso filtramos aqui, do lado
de fora, usando os mesmos padrões do `.code-intelligence.json`.
"""
import fnmatch
import json
import os
import subprocess
import sys

MARCADOR = "<!-- code-intelligence-bot -->"


def carregar_exclude_globs():
    try:
        with open(".code-intelligence.json", encoding="utf-8") as f:
            return json.load(f).get("exclude_globs", [])
    except (FileNotFoundError, json.JSONDecodeError):
        return []


def excluido(caminho, padroes):
    return any(fnmatch.fnmatch(caminho, p) or fnmatch.fnmatch("/" + caminho, p) for p in padroes)


def rodar_diff():
    base_sha = os.environ["BASE_SHA"]
    cli = os.path.join(".github", "code_intelligence", "code-intelligence.py")
    return subprocess.run(
        [sys.executable, cli, "diff", base_sha, "--root", "."],
        capture_output=True, text=True,
    )


def formatar(payload):
    regressoes = payload.get("regressions", [])
    if not regressoes:
        return (
            f"{MARCADOR}\n"
            "## ✅ Code Intelligence — nenhuma violação nova\n\n"
            "Este PR não introduz nenhuma violação de qualidade de código em relação à `main`."
        )
    linhas = [
        f"- **[{v['severity']}] {v['code']}** em `{v['file']}:{v['line']}` — {v['message']}"
        for v in regressoes
    ]
    return (
        f"{MARCADOR}\n"
        "## ❌ Code Intelligence — violações novas detectadas\n\n"
        f"Este PR introduz {len(regressoes)} violação(ões) de qualidade que não existiam na `main`:\n\n"
        + "\n".join(linhas)
        + "\n\n---\n*Verificação automática (naming, docstrings, tamanho, duplicação, etc). "
          "Corrija os pontos acima — um novo push reavalia automaticamente.*"
    )


def escrever_artefato_comentario(corpo):
    """Grava o corpo do comentário e o número do PR em arquivos, para que o
    workflow 'Comentar resultados dos checks no PR' (acionado via
    workflow_run, que roda com permissão de escrita mesmo para PRs de fork)
    os publique depois. PRs de fork recebem um GITHUB_TOKEN somente leitura
    em workflows disparados por pull_request — por isso nunca tentamos
    comentar diretamente aqui."""
    with open("/tmp/comentario.md", "w", encoding="utf-8") as f:
        f.write(corpo)
    with open("/tmp/pr_number.txt", "w", encoding="utf-8") as f:
        f.write(os.environ["PR_NUMBER"])


def main():
    resultado = rodar_diff()
    try:
        payload = json.loads(resultado.stdout)
    except json.JSONDecodeError:
        erro = (
            "## 💥 Code Intelligence — erro ao rodar a verificação\n\n"
            "A ferramenta não retornou uma saída válida (isso é um problema na verificação "
            "em si, não no seu código). Avise o professor com o link deste job.\n\n"
            f"stdout:\n```\n{resultado.stdout[:2000]}\n```\n"
            f"stderr:\n```\n{resultado.stderr[:2000]}\n```"
        )
        print("erro: saída inesperada do code-intelligence", file=sys.stderr)
        print("stdout:", resultado.stdout, file=sys.stderr)
        print("stderr:", resultado.stderr, file=sys.stderr)
        with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
            f.write(erro + "\n")
        sys.exit(1)

    padroes = carregar_exclude_globs()
    payload["regressions"] = [
        v for v in payload.get("regressions", []) if not excluido(v["file"], padroes)
    ]

    regressoes = payload.get("regressions", [])
    corpo = formatar(payload)
    with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
        f.write(corpo + "\n")
    escrever_artefato_comentario(corpo)
    if regressoes:
        print(f"CODE INTELLIGENCE — REPROVADO ({len(regressoes)} violação(ões) nova(s))")
        for v in regressoes:
            print(f"  [{v['severity']}] {v['code']} em {v['file']}:{v['line']} — {v['message']}")
        print("\nVeja o Summary desta execução (aba 'Summary' do job) para o veredito completo e como corrigir.")
    else:
        print("CODE INTELLIGENCE — APROVADO (nenhuma violação nova)")
    sys.exit(2 if regressoes else 0)


if __name__ == "__main__":
    main()
