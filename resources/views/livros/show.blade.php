<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Livro</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tela-pagina">
    <div class="tabela-container">
        <h1 class="titulo-pagina">{{ $livro->LVRTITULO }}</h1>
        <p class="text-gray-700 mb-2"><strong>Editora:</strong> {{ $livro->LVREDITORA ?? 'N/A' }}</p>
        <p class="text-gray-700 mb-4"><strong>Exemplares Disponíveis:</strong> {{ $exemplaresDisponiveisCount }}</p>
        <a href="{{ route('livros.index') }}" class="link">← Voltar para a listagem</a>
    </div>
</body>
</html>
