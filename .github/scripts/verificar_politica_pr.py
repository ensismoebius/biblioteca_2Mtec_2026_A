#!/usr/bin/env python3
"""Verifica a política de tamanho de Pull Requests do projeto.

Regras:
1. Um arquivo já existente não pode ser reescrito quase por inteiro
   (linhas alteradas / linhas do arquivo original >= LIMIAR_REESCRITA).
2. Um PR "grande" (total de linhas alteradas >= LIMIAR_PR_GRANDE) só é
   aceito se TODOS os arquivos tocados forem arquivos novos (criação).

Arquivos gerados automaticamente (lockfiles, migrations) são ignorados
nas duas regras.
"""
import fnmatch
import os
import subprocess
import sys

LIMIAR_REESCRITA = 0.90
LIMIAR_PR_GRANDE = 300

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


def excluido(caminho):
    return any(fnmatch.fnmatch(caminho, padrao) for padrao in EXCLUIR)


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
    total_linhas = 0
    tem_arquivo_nao_novo = False
    detalhes_grandes = []

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

        total_linhas += alteradas
        detalhes_grandes.append(f"  - `{caminho}` ({status}): {alteradas} linhas")
        if status != "A":
            tem_arquivo_nao_novo = True

        if status == "M":
            base_conteudo = subprocess.run(
                ["git", "show", f"{base}:{caminho}"],
                capture_output=True, text=True
            )
            if base_conteudo.returncode == 0:
                linhas_base = base_conteudo.stdout.count("\n") or 1
                proporcao = alteradas / linhas_base
                if proporcao >= LIMIAR_REESCRITA:
                    violacoes.append(
                        f"- **`{caminho}`** foi alterado em {proporcao:.0%} das suas linhas "
                        f"({alteradas} de {linhas_base}). Isso viola a política de pequenas "
                        f"alterações — quebre esta mudança em PRs menores e incrementais."
                    )

    if total_linhas >= LIMIAR_PR_GRANDE and tem_arquivo_nao_novo:
        violacoes.append(
            f"- Este PR altera **{total_linhas} linhas** (limite: {LIMIAR_PR_GRANDE}) e inclui "
            f"arquivos que já existiam antes do PR. PRs grandes só são aceitos quando **todos** "
            f"os arquivos tocados são novos (criação). Divida esta mudança em PRs menores.\n\n"
            f"  Arquivos neste PR:\n" + "\n".join(detalhes_grandes)
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
        "Nenhuma reescrita total de arquivo e nenhum PR grande com arquivos não-novos detectado."
    )
    with open(os.environ["GITHUB_STEP_SUMMARY"], "a") as f:
        f.write(corpo + "\n")
    with open("/tmp/comentario.md", "w") as f:
        f.write(corpo)
    with open("/tmp/pr_number.txt", "w") as f:
        f.write(os.environ["PR_NUMBER"])


if __name__ == "__main__":
    main()
