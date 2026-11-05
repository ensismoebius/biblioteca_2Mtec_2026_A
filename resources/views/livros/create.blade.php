<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Livro - Acervo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-3xl mx-auto bg-white shadow-md rounded-lg p-6">
        <h1 class="text-3xl font-bold text-indigo-600 mb-6">Cadastrar Novo Livro</h1>

        <form action="{{ route('livros.store') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Título</label>
                <input type="text" name="LVRTITULO" required class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">ISBN</label>
                <input type="text" name="LVRISBN" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Editora</label>
                <input type="text" name="LVREDITORA" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Gênero</label>
                <select name="generos[]" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    <option value="">Selecione um gênero</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->GNRCODIGO }}">{{ $genero->GNRNOME }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Autor</label>
                <select name="autores[]" class="w-full border-gray-300 rounded-md shadow-sm p-2 border">
                    <option value="">Selecione um autor</option>
                    @foreach($autores as $autor)
                        <option value="{{ $autor->AUTCODIGO }}">{{ $autor->AUTNOME }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('livros.index') }}" class="text-gray-600 hover:underline">← Voltar</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md font-semibold hover:bg-indigo-700">Salvar Livro</button>
            </div>
        </form>
    </div>
</body>
</html>
