<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Livro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-4xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold text-indigo-600 mb-4">{{ $livro->LVRTITULO }}</h1>
        <p class="text-gray-700 mb-2"><strong>Editora:</strong> {{ $livro->LVREDITORA ?? 'N/A' }}</p>
        <p class="text-gray-700 mb-4"><strong>Exemplares Disponíveis:</strong> {{ $exemplaresDisponiveisCount }}</p>

        <a href="{{ route('livros.index') }}" class="text-indigo-600 hover:underline">&larr; Voltar para a listagem</a>
    </div>
</body>
</html>
