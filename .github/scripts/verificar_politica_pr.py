#!/usr/bin/env python3
"""Verifica a política de tamanho de Pull Requests do projeto.

Regras:
1. Um arquivo já existente não pode ser reescrito quase por inteiro
   (linhas alteradas / linhas do arquivo original >= LIMIAR_REESCRITA) —
   mas só conta quando o arquivo já é grande o bastante para isso fazer
   sentido: o arquivo original precisa ter pelo menos TAMANHO_MINIMO_REESCRITA
   linhas (o limiar "warning" de tamanho de arquivo do Code Intelligence,
   lido de `.github/code_intelligence/default_config.json` com o override
   de `.code-intelligence.json`, se houver). Reescrever 90% de um arquivo
   pequeno não é um problema de verdade.
2. Nenhum arquivo já existente (não-novo) pode ter mais de
   LIMIAR_ARQUIVO_GRANDE linhas alteradas — o limite é por arquivo, não
   somado entre todos os arquivos do PR.
3. Nenhuma mudança é aceita dentro de `.github/workflows/` — só o professor
   altera a esteira de CI.

Arquivos gerados automaticamente (lockfiles, migrations) são ignorados
nas regras 1 e 2.
"""
import fnmatch
import json
import os
import subprocess
import sys
from pathlib import Path

LIMIAR_REESCRITA = 0.90
LIMIAR_ARQUIVO_GRANDE = 300
TAMANHO_MINIMO_REESCRITA_PADRAO = 500

EXCLUIR = [
    "composer.lock",
    "package-lock.json",
    "npm-shrinkwrap.json",
    "yarn.lock",
    "*.lock",
    "database/migrations/*",
    "public/build/*",
    "public/hot",
]

CAMINHO_WORKFLOWS = ".github/workflows/"


def excluido(caminho):
    return any(fnmatch.fnmatch(caminho, padrao) for padrao in EXCLUIR)


def carregar_tamanho_minimo_reescrita():
    """Lê o limiar 'warning' de tamanho de arquivo do Code Intelligence
    (default_config.json com override de .code-intelligence.json), para
    usar como tamanho mínimo de arquivo antes da regra de reescrita (90%)
    valer. Em caso de qualquer problema, cai de volta no valor padrão."""
    try:
        with open(".github/code_intelligence/default_config.json", encoding="utf-8") as f:
            valor = json.load(f)["loc_thresholds"]["warning"]
        if Path(".code-intelligence.json").exists():
            with open(".code-intelligence.json", encoding="utf-8") as f:
                override = json.load(f)
            valor = override.get("loc_thresholds", {}).get("warning", valor)
        return int(valor)
    except Exception:
        return TAMANHO_MINIMO_REESCRITA_PADRAO


def rodar(cmd):
    r = subprocess.run(cmd, capture_output=True, text=True)
    if r.returncode not in (0, 1):
        print(r.stderr, file=sys.stderr)
        sys.exit(r.returncode)
    return r.stdout


def main():
    base = os.environ["BASE_SHA"]
    head = os.environ["HEAD_SHA"]

    status_saida = rodar(["git", "diff", "--name-status", f"{base}...{head}"])
    numstat_saida = rodar(["git", "diff", "--numstat", f"{base}...{head}"])

    status_por_arquivo = {}
    for linha in status_saida.splitlines():
        if not linha.strip():
            continue
        partes = linha.split("\t")
        codigo = partes[0]
        caminho = partes[-1]
        status_por_arquivo[caminho] = codigo[0]  # A, M, D, R...

    violacoes = []

    # Regra 3: ninguém além do professor mexe em .github/workflows/.
    workflows_tocados = sorted(
        caminho for caminho in status_por_arquivo if caminho.startswith(CAMINHO_WORKFLOWS)
    )
    if workflows_tocados:
        lista = "\n".join(f"  - `{c}`" for c in workflows_tocados)
        violacoes.append(
            "- Este PR altera arquivos dentro de `.github/workflows/` — isso não é "
            "permitido. Só o professor pode mudar a esteira de CI. Reverta essas "
            f"mudanças (ou abra uma conversa com o professor se achar que a esteira "
            f"precisa mudar):\n{lista}"
        )

    tamanho_minimo_reescrita = carregar_tamanho_minimo_reescrita()

    for linha in numstat_saida.splitlines():
        if not linha.strip():
            continue
        partes = linha.split("\t")
        if len(partes) < 3:
            continue
        add_s, del_s, caminho = partes[0], partes[1], partes[2]
        if "=>" in caminho:  # renomeações no formato "a/{b => c}"
            caminho = caminho.split("=>")[-1].strip(" {}")
        if excluido(caminho):
            continue

        add = 0 if add_s == "-" else int(add_s)
        rem = 0 if del_s == "-" else int(del_s)
        alteradas = add + rem
        status = status_por_arquivo.get(caminho, "M")

        # Regra 2: limite de linhas alteradas por arquivo (não somado no PR).
        if status != "A" and alteradas >= LIMIAR_ARQUIVO_GRANDE:
            violacoes.append(
                f"- **`{caminho}`** teve **{alteradas} linhas alteradas** (limite: "
                f"{LIMIAR_ARQUIVO_GRANDE} por arquivo). Divida as mudanças nesse "
                f"arquivo em PRs menores e incrementais."
            )

        # Regra 1: reescrita quase total, só vale para arquivos grandes o bastante.
        if status == "M":
            base_conteudo = subprocess.run(
                ["git", "show", f"{base}:{caminho}"],
                capture_output=True, text=True
            )
            if base_conteudo.returncode == 0:
                linhas_base = base_conteudo.stdout.count("\n") or 1
                if linhas_base >= tamanho_minimo_reescrita:
                    proporcao = alteradas / linhas_base
                    if proporcao >= LIMIAR_REESCRITA:
                        violacoes.append(
                            f"- **`{caminho}`** foi alterado em {proporcao:.0%} das suas linhas "
                            f"({alteradas} de {linhas_base}, arquivo com pelo menos "
                            f"{tamanho_minimo_reescrita} linhas). Isso viola a política de "
                            f"pequenas alterações — quebre esta mudança em PRs menores e "
                            f"incrementais."
                        )

    if violacoes:
        print("POLÍTICA DE PULL REQUESTS — REPROVADO\n")
        print("\n".join(violacoes))
        corpo = (
            "<!-- pr-policy-bot -->\n"
            "## ❌ Política de Pull Requests reprovada\n\n"
            + "\n".join(violacoes) +
            "\n\n---\n*Verificação automática. Ajuste o PR e um novo push irá reavaliar.*"
        )
        with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
            f.write(corpo + "\n")
        with open("/tmp/comentario.md", "w") as f:
            f.write(corpo)
        with open("/tmp/pr_number.txt", "w") as f:
            f.write(os.environ["PR_NUMBER"])
        sys.exit(1)

    print("POLÍTICA DE PULL REQUESTS — APROVADO")
    corpo = (
        "<!-- pr-policy-bot -->\n"
        "## ✅ Política de Pull Requests aprovada\n\n"
        "Nenhuma reescrita total de arquivo grande, nenhum arquivo com mudanças "
        "demais e nenhuma alteração em `.github/workflows/` detectada."
    )
    with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
        f.write(corpo + "\n")
    with open("/tmp/comentario.md", "w") as f:
        f.write(corpo)
    with open("/tmp/pr_number.txt", "w") as f:
        f.write(os.environ["PR_NUMBER"])


if __name__ == "__main__":
    main()
