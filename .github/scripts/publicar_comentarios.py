#!/usr/bin/env python3
"""Publica/atualiza comentários de PR a partir de artefatos baixados de uma
execução anterior (via workflow_run).

Cada check (pint, política de PR, code-intelligence, issue vinculada) roda
disparado por `pull_request` e, para PRs de fork, recebe um GITHUB_TOKEN
somente leitura — nunca consegue comentar diretamente. Por isso cada um
apenas grava seu veredito em `/tmp/comentario.md` + `/tmp/pr_number.txt` e
os publica como artefato. Este script roda num workflow separado, disparado
por `workflow_run`, que usa a versão do workflow do branch padrão do
repositório (nunca a do fork) e por isso recebe um token com permissão de
escrita — é o único ponto de todo o pipeline que efetivamente comenta em
PRs de fork.
"""
import glob
import json
import os
import re
import subprocess
import sys

REPO = os.environ["GITHUB_REPOSITORY"]


def extrair_marcador(corpo: str) -> str | None:
    m = re.match(r"^(<!--\s*[\w-]+-bot\s*-->)", corpo.strip())
    return m.group(1) if m else None


def publicar(pr_number: str, corpo: str) -> None:
    marcador = extrair_marcador(corpo)
    if not marcador:
        print(f"aviso: comentário sem marcador reconhecível, pulando (PR #{pr_number})", file=sys.stderr)
        return
    lista = subprocess.run(
        ["gh", "api", f"repos/{REPO}/issues/{pr_number}/comments", "--paginate"],
        capture_output=True, text=True,
    )
    comentarios = json.loads(lista.stdout or "[]") if lista.returncode == 0 else []
    existente = next((c for c in comentarios if marcador in c.get("body", "")), None)
    payload = json.dumps({"body": corpo})
    if existente:
        r = subprocess.run(
            ["gh", "api", f"repos/{REPO}/issues/comments/{existente['id']}", "-X", "PATCH", "--input", "-"],
            input=payload, text=True, capture_output=True,
        )
    else:
        r = subprocess.run(
            ["gh", "api", f"repos/{REPO}/issues/{pr_number}/comments", "-X", "POST", "--input", "-"],
            input=payload, text=True, capture_output=True,
        )
    if r.returncode != 0:
        print(f"erro ao publicar comentário no PR #{pr_number}: {r.stderr}", file=sys.stderr)
    else:
        print(f"comentário publicado/atualizado no PR #{pr_number} ({marcador})")


def main() -> None:
    base_dir = sys.argv[1] if len(sys.argv) > 1 else "comentarios"
    encontrou = False
    for pr_file in glob.glob(os.path.join(base_dir, "*", "pr_number.txt")):
        pasta = os.path.dirname(pr_file)
        corpo_path = os.path.join(pasta, "comentario.md")
        if not os.path.exists(corpo_path):
            continue
        with open(pr_file, encoding="utf-8") as f:
            pr_number = f.read().strip()
        with open(corpo_path, encoding="utf-8") as f:
            corpo = f.read()
        if not pr_number:
            continue
        encontrou = True
        publicar(pr_number, corpo)
    if not encontrou:
        print("nenhum artefato de comentário encontrado (nada a publicar)")


if __name__ == "__main__":
    main()
