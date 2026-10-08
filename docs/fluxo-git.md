# Aqui você irá aprender o fluxo do projeto

Iremos te ensinar desde como clonar o projeto, até o merge do Pull Request nesse guia

## Git Fork

Primeiramente iremos criar uma cópia do projeto em seu perfil.

Vá para a página do projeto no perfil do professor ([Link para a página do professor](https://github.com/ensismoebius/biblioteca_2Mtec_2026_A)), e enquanto logado em seu perfil, clique no botão de Fork no to topo superior direito da tela. Você será redirecionado(a) para uma tela de opções, onde você poderá adicionar um nome e uma descrição própria (ou manter a do projeto original), se será privado ou público e mais algumas opções.

## Git Clone

Após você ter terminado de dar um Fork no projeto, você precisa clonar o projeto que você tem agora. Precisaremos do link do seu projeto para clonar-lo. Você pode simplesmente copiar o link da barra de pesquisa, ou você pode apertar o botão verde de "Code" e clicar no botão do lado do link para copiar.

Aqui será ensinado a como fazer o git clone pelo VSCode, mas você pode fazer do jeito que prefirir. Para isso será necessário que você tenha o git instalado na máquina ([Link para instalação do git](https://git-scm.com/install/)).

Abra o Visual Studio Code, na barra lateral esquerda, haverá um icone do git. Clique-o para abrir o menu dele, lá terá um botão de clonar repositório (o texto irá depender de qual idioma você está usando, mas em geral terá o texto de clonar projeto ou reposítorio). Irá ser aberto um pop-up, onde lá você deve colocar o link de seu repositório que você tinha copiado mais cedo. Você poderá escolher em qual lugar do computador guardar esse projeto, guarde-o em um lugar que você vai conseguir lembrar depois. Após a finalização da clonagem, terá a opção de abrir o projeto, abra-o.

## Branches

Aqui iremos criar uma Branch nova para podemos fazer nossas alterações do projeto. A criação dessas Branches faz com que a organização fique melhor. Separando as alterações partes fácies de entender e arrumar

O método para criar e usar as Branches varia, mas aqui iremos ensinar como criar e usar Branches usando o terminal mesmo.

Dentro do terminal, use o comando 

**Terminal** 
```
"git checkout -b <Tipo/Issue-Numéro da Issue-descricao-curta>" 
```

Isso cria uma Branch e já troca para ela.

Após isso, você poderá fazer todas as alterações que precisas para resolver a issue

## Git commit e git push 

Após fazer todas as alterações e ter certeza que está tudo correto, iremos finalmente enviar as alterações para o repositório.

Certificando-se que você está na Branch que você criou e não na main/principal, iremos executar alguns comandos, sendo eles
**Terminal**
```powershell
git add . 
git commit -m "<Tipo>/<issue>-<Numero da issue >-<descricao-curta>"
git pull
git push 
```

Esses comandos irão, em uma analogia estranha:
- Colocar as alterações em uma caixa
- Fechar ela e colocar um nome
- Ver se tem coisas de outras pessoas para colocar na caixa
- Enviar a caixa para o repositório

Isso irá enviar para o repósitorio um Pull Request (PR) no github, onde irá será necessário o preenchimento das infomações sobre o Pull Request. O template do preenchimento pode ser achado em ".github/pull_request-template.md"


# Links úteis

[Commits do git hub](https://github.com/git-guides/git-commit)
[Push do git hub](https://github.com/git-guides/git-push)
       