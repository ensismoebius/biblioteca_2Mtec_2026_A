# 📚 Biblioteca 2º Mtec 2026 — Turma A

Sistema de gestão de biblioteca escolar, desenvolvido em **Laravel** pelos
estudantes do 2º ano do curso técnico em Desenvolvimento de Sistemas (Etec),
como projeto integrador entre as disciplinas de **Programação** e
**Banco de Dados**.

## Sobre o projeto

A escola precisa controlar seu acervo de livros, os exemplares físicos
disponíveis, os leitores cadastrados e o ciclo de empréstimo e devolução —
hoje um processo manual e sujeito a erro. Este projeto constrói um sistema
web para resolver isso: cadastro de livros/autores/gêneros, controle de
exemplares por cópia física, cadastro de leitores e funcionários com
diferentes níveis de acesso, o fluxo completo de empréstimo/devolução com
regras de negócio (faixa etária, limite de empréstimos simultâneos, prazos e
atrasos) e relatórios gerenciais para a equipe da biblioteca.

O projeto tem dois objetivos didáticos simultâneos:

- **Metodologia ágil (Scrum)** — o trabalho está organizado em 4 sprints de
  2 semanas, com issues, milestones, papéis rotativos e cerimônias
  (Planning, Daily, Review, Retrospectiva). Veja as
  [milestones](../../milestones) e as [issues](../../issues) do repositório.
- **Laravel e PHP** — a aplicação é construída sobre um modelo de dados já
  definido (ver `docs/` assim que o diagrama for adicionado), integrando com
  o trabalho da disciplina de Banco de Dados.

Este repositório é a Turma A. Existe uma segunda turma trabalhando no
mesmo backlog, em paralelo e de forma independente, com issues e regras
idênticas — cada turma entrega sua própria implementação.

**Prazo final: 15/11/2026** (apresentação do projeto).

## Stack técnica

- **Laravel 12** + PHP 8.3
- **Blade** + **Tailwind CSS** (via Laravel Breeze)
- **MySQL 8**
- **Vite** para build de assets front-end
- **GitHub Actions** para CI (veja "Regras do projeto" abaixo)

## Configuração do ambiente local

Não é preciso instalar PHP, Composer, Node ou nada disso no seu computador
(inclusive em computador da escola, sem privilégios de administrador): o
projeto usa o [Portaravel](https://github.com/ensismoebius/portaravel), um
ambiente Laravel portátil — extrai e roda. O único pré-requisito é ter um
**MySQL local rodando** (o mesmo que você já configurou na disciplina de
Banco de Dados).

> ⚠️ **Importante:** os comandos `artisan`, `composer`, `npm` e `php` só
> funcionam corretamente **dentro do ambiente do Portaravel**, aberto via
> `./shell.sh` (Linux) ou `shell.bat` (Windows). Fora desse shell, seu
> terminal normal não conhece esses comandos (ou, se você tiver PHP/Composer
> instalados no sistema, eles podem apontar para versões erradas e dar erro
> estranho). Isso vale não só para a configuração inicial abaixo, mas para
> **todo o dia a dia** do projeto — sempre que for rodar `artisan
> make:model`, `composer require`, etc., abra o shell primeiro.

### 1. Faça um fork e clone o seu fork

No GitHub, clique em **Fork** no topo deste repositório para criar sua
própria cópia. Depois clone **o seu fork** (não este repositório original):

```bash
git clone https://github.com/SEU-USUARIO/biblioteca_2Mtec_2026_A.git
```

Recomendado: adicione este repositório original como `upstream`, para
conseguir trazer atualizações (novas issues, correções de CI, etc.) para o
seu fork mais tarde:

```bash
cd biblioteca_2Mtec_2026_A
git remote add upstream https://github.com/ensismoebius/biblioteca_2Mtec_2026_A.git
cd ..
```

### 2. Baixe o Portaravel

**Linux:**
```bash
curl -fL --progress-bar https://github.com/ensismoebius/portaravel/releases/latest/download/portable-laravel-linux.tar.gz | tar -xz
cd portable-laravel-linux
```

**Windows (PowerShell):**
```powershell
irm https://github.com/ensismoebius/portaravel/releases/latest/download/portable-laravel-windows.zip -OutFile pl.zip
Expand-Archive pl.zip -DestinationPath .
cd portable-laravel-windows
```

### 3. Ajuste os scripts do Portaravel para usar MySQL

Por padrão, o Portaravel roda com um banco SQLite embutido: os scripts
`_env.sh`/`_env.bat` sobrescrevem `DB_CONNECTION`/`DB_DATABASE` como
variáveis de ambiente, o que ignora silenciosamente o que você configurar
no `.env` (Laravel dá prioridade à variável de ambiente). Como este
projeto usa **MySQL** (ver "Stack técnica"), é preciso corrigir isso uma
única vez, editando 4 arquivos para forçar `mysql` logo depois que eles
carregam o `_env.sh`/`_env.bat`.

**Linux** — em `shell.sh`, `run.sh`, `artisan.sh` e `composer.sh`,
adicione estas duas linhas logo após a linha `source "$DIST_ROOT/_env.sh"`:
```bash
export DB_CONNECTION=mysql
export DB_DATABASE=biblioteca
```

Ou rode este comando (uma vez, na pasta do Portaravel) para aplicar nos
quatro arquivos de uma vez:
```bash
for f in shell.sh run.sh artisan.sh composer.sh; do
  sed -i '/source.*_env\.sh/a export DB_CONNECTION=mysql\nexport DB_DATABASE=biblioteca' "$f"
done
```

**Windows** — em `shell.bat`, `run.bat`, `artisan.bat` e `composer.bat`,
adicione estas duas linhas logo após a linha `call "%DIST_ROOT%\_env.bat"`:
```bat
set "DB_CONNECTION=mysql"
set "DB_DATABASE=biblioteca"
```

### 4. Troque a pasta `app/` pelo seu clone

O Portaravel vem com um Laravel de exemplo em `app/` — substitua pelo
repositório que você acabou de clonar (mova a pasta clonada do passo 1
para dentro do Portaravel, renomeada para `app`):

**Linux:**
```bash
rm -rf app
mv ../biblioteca_2Mtec_2026_A app
```

**Windows (CMD):**
```cmd
rmdir /s /q app
move ..\biblioteca_2Mtec_2026_A app
```

### 5. Instale as dependências e configure o `.env`

Abra o shell de desenvolvimento (`php`, `composer`, `npm`, `artisan` já
ficam disponíveis, sem precisar dos `.sh`/`.bat`): `./shell.sh` no Linux,
`shell.bat` no Windows.

Dentro do shell:

**Linux:**
```bash
composer install
npm install
cp .env.example .env
artisan key:generate
```

**Windows:**
```cmd
composer install
npm install
copy .env.example .env
artisan key:generate
```

Abra o `.env` gerado e confira `DB_DATABASE`, `DB_USERNAME` e `DB_PASSWORD`
— já vem pré-preenchido para um MySQL local padrão (`root`, sem senha, em
`127.0.0.1:3306`, banco `biblioteca`). Ajuste se o seu MySQL local usa
outras credenciais.

### 6. Crie o banco e rode as migrations

Crie o banco `biblioteca` no seu MySQL local — pela linha de comando
(`mysql -u root -e "CREATE DATABASE IF NOT EXISTS biblioteca;"`), pelo
phpMyAdmin, MySQL Workbench ou o que preferir. Depois, ainda dentro do
`./shell.sh`/`shell.bat`:

```bash
artisan migrate
```

### 7. Rode o projeto

Saia do shell (`exit`) e inicie o servidor: `./run.sh` no Linux, ou
`run.bat` no Windows (dois cliques também funciona).

O navegador abre automaticamente em **http://127.0.0.1:8080**. Se aparecer
a tela do Laravel/Breeze, está tudo funcionando.

### 8. Rode o Pint antes de abrir o PR

O projeto usa o [Laravel Pint](https://laravel.com/docs/pint) para estilo
de código, e o CI reprova o PR se houver qualquer problema (veja "Regras
do projeto" abaixo). Rode sempre dentro do `./shell.sh`/`shell.bat`, na
pasta `app/`:

```bash
./vendor/bin/pint
```

Sem argumentos, o Pint **corrige automaticamente** os problemas que
encontrar — revise o que ele mudou, adicione ao commit e dê push. Se você
só quer conferir sem alterar nada (é o que o CI roda), use:

```bash
./vendor/bin/pint --test
```

Migrations (`database/migrations/**`) são ignoradas pelo Pint (configurado
em `pint.json`, na raiz do repositório) — só o restante do código
(models, controllers, requests, etc.) é verificado.

### IDE

Sempre abra a pasta `app/` no seu editor (VSCode, PHPStorm, etc.) — **não**
a raiz do Portaravel. O passo a passo completo de configuração de IDE
(caminho do PHP, Xdebug, extensões recomendadas) está no
[README do Portaravel](https://github.com/ensismoebius/portaravel#-english).

## Como contribuir

1. Escolha uma issue aberta e sem responsável, atribua-a a si mesmo.
2. Crie uma branch a partir da `main` (padrão de nome: `tipo/issue-NN-descricao-curta`).
3. Faça commits pequenos e frequentes.
4. Abra um Pull Request para a `main` e peça revisão do time.
5. Corrija o que o time e os checks automáticos (abaixo) apontarem.
6. O PR é mesclado assim que aprovado e com os checks verdes.

Consulte `docs/fluxo-git.md` (a ser escrito pelo time, veja as issues de
Infra da Sprint 1) para o passo a passo completo.

## Regras do projeto (proteção de branch e CI)

A branch `main` é protegida. Nenhum push direto é aceito — toda mudança
entra por Pull Request. Para um PR ser aceito, **todas** as regras abaixo
precisam estar satisfeitas ao mesmo tempo:

### 1. Três aprovações
Um Pull Request só pode ser mesclado depois de **aprovado por pelo menos 3
integrantes do time** (não conta a própria pessoa que abriu o PR).

### 2. Política de tamanho de Pull Request (`verificar-politica-pr`)
Um bot analisa automaticamente o diff do PR e reprova quando:
- **Um arquivo já existente é reescrito quase por inteiro** (90% ou mais das
  suas linhas alteradas). Prefira mudanças pequenas e incrementais no lugar
  de reescrever um arquivo do zero.
- **O PR é grande** (300 linhas alteradas ou mais) **e** inclui arquivos que
  já existiam antes do PR. PRs grandes só são aceitos quando **todos** os
  arquivos tocados são novos (criação) — por exemplo, adicionar várias
  telas novas de uma vez é permitido; reescrever várias telas existentes de
  uma vez, não.
- Arquivos gerados automaticamente (`composer.lock`, `package-lock.json`,
  migrations) não entram nesse cálculo.

O resultado aparece como um comentário automático no próprio PR, explicando
exatamente o que precisa ser dividido ou ajustado.

### 3. Code Intelligence — qualidade de código (`code-intelligence`)
Um segundo bot roda uma análise estática do código PHP alterado no PR,
comparando com a `main`, e **reprova se o PR introduzir qualquer violação
nova** que não existia antes (tolerância zero) — por exemplo:

- Nomes de variável/parâmetro/método com menos de 3 caracteres
- Classe ou método sem comentário de documentação (`/** ... */`)
- Mais de uma classe por arquivo, ou classe com nome diferente do arquivo
- Funções/arquivos muito longos (acima dos limites configurados)

Templates Blade (`*.blade.php`) e migrations (`database/migrations/**`) são
**excluídos** dessa análise — a ferramenta analisa apenas código PHP
"normal" (models, controllers, requests, services, etc). A configuração
completa está em `.code-intelligence.json`, na raiz do repositório.

O resultado também aparece como um comentário automático no PR, listando
cada violação nova com arquivo, linha e explicação. Corrigir o problema e
dar um novo push reavalia automaticamente — não é preciso fechar e reabrir
o PR.

### 4. Issue vinculada (`verificar-issue-vinculada`)
Todo PR **precisa** referenciar, na descrição, a issue que ele resolve,
usando uma das palavras-chave de fechamento automático do GitHub:
`Closes #N`, `Fixes #N` ou `Resolves #N` (aceita variações como
`closed`/`fixed`/`resolved`, em qualquer posição do texto). O número
precisa ser de uma issue existente e aberta no repositório.

Isso não é burocracia: é o que faz o **próprio GitHub fechar a issue
automaticamente** assim que o PR é mesclado na `main` — ninguém precisa
fechar issues manualmente. O template de PR do repositório já vem com o
campo `Closes #` pronto para preencher.

### O que fazer se o PR for reprovado
1. Leia o comentário do bot que reprovou (política de PR, Code
   Intelligence ou Issue Vinculada) — ele explica exatamente o motivo.
2. Corrija o código, a descrição do PR, ou divida o PR em partes menores.
3. Dê um novo `git push` (ou edite a descrição do PR) — os checks rodam de
   novo automaticamente.
4. Se você acha que a reprovação é um falso positivo, converse com o
   professor antes de tentar contornar a regra.

## Modelo de dados

_(o diagrama ER e a documentação do schema serão adicionados aqui pelo time
— veja as issues de Banco de Dados da Sprint 1)._

## Equipe

Cada integrante do time adiciona sua própria linha abaixo, através de um
Pull Request individual (veja a issue "Adicionar seu nome ao README").

| Nome completo | Nick | Registro de matrícula |
|---|---|---|
| Caio da Silva Matias | peba00 | 10247 | 
| Heloisa Aguilera da Paixão | helopaixao | 10583 |
| Carolyne Mudesto de Matos | mudestocarol | 10517 |
| Pedro Henrique Santos Silva| pedrosilva18560 | 10244 |
| Gabriel Ornelas de Aguilar| ornelasXXtentationXX | 10520 |
| Arthur Comarim de Freitas Arcanjo | Arthur-Comarim | 10378 |
| Emanuel dos Santos Batista | Linuel14 | 10382 |
|Cesar Augusto Denelle Mussi | Meganoot2010 | 10463 |
| Gustavo Porfirio dos Santos | GustavoP55 | 10251 |
| Laura Kelly OLIVEIRA ALMEIDA | koallaura | 10249 |
| Eric Richard Silveira | ersgd21 | 10273 |
| Esmeralda Amorim do Nascimento | amorimesme | 10386 |
| Agatha de Paula Fernandes | thagax | 10516 |
| Gabriel de brito simao | gabrielbritosimao-crypto| 10379|
