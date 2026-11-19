<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Livro - Acervo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tela-pagina">
    <div class="tabela-container">
        <h1 class="titulo-pagina">{{ $livro->LVRTITULO }}</h1>

        <div class="detalhe-item">
            <span class="detalhe-rotulo">Editora:</span> {{ $livro->LVREDITORA ?? 'N/A' }}
        </div>

        <div class="detalhe-item">
            <span class="detalhe-rotulo">Exemplares Disponíveis:</span> {{ $exemplaresDisponiveisCount ?? 0 }}
        </div>

        <div class="mt-6">
            <a href="{{ route('livros.index') }}">
                <x-botao variante="secundario" class="btn-secundario">← Voltar para a listagem</x-botao>
            </a>
        </div>
    </div>
</body>
</html>
