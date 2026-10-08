\# Componentes Blade



Componentes reutilizáveis em `resources/views/components/`.



\## x-alerta



Mensagem para o usuário. `tipo`: `sucesso` (padrão), `erro` ou `aviso`.



```blade

<x-alerta tipo="sucesso">Salvo com sucesso!</x-alerta>

<x-alerta tipo="erro">Algo deu errado.</x-alerta>

<x-alerta tipo="aviso">Atenção.</x-alerta>

```



\## x-botao



Botão estilizado. `variante`: `primario` (padrão), `secundario` ou `perigo`.



```blade

<x-botao variante="primario">Salvar</x-botao>

<x-botao variante="perigo" type="submit">Excluir</x-botao>

```



\## x-badge



Etiqueta de status. `status`: `disponivel` (padrão), `emprestado` ou `atrasado`.



```blade

<x-badge status="emprestado">Emprestado</x-badge>

```



\## x-tabela



Tabela com linhas zebradas e destaque ao passar o mouse. O cabeçalho e as linhas vão dentro da tag.



```blade

<x-tabela>

&#x20;   <thead><tr><th>Título</th><th>Autor</th></tr></thead>

&#x20;   <tbody>

&#x20;       <tr><td>Dom Casmurro</td><td>Machado de Assis</td></tr>

&#x20;   </tbody>

</x-tabela>

```



\## x-campo



Rótulo, input, texto de ajuda e mensagem de erro de validação do campo. Props: `name` (obrigatória), `label`, `ajuda`, `type` (padrão `text`) e `value`. Mantém o valor digitado com `old()` quando a validação falha.



```blade

<x-campo name="titulo" label="Título" ajuda="Nome do livro" />

```

