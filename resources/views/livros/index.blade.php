<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Livros - Acervo</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 p-8">
    <div class="max-w-6xl mx-auto bg-white shadow-md rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-indigo-600">Lista de Livros</h1>
            <a href="{{ route('livros.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-indigo-700">Novo Livro</a>
        </div>

        <!-- Formulário de Busca e Filtros (Issue #40) -->
        <form method="GET" action="{{ route('livros.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 bg-gray-50 p-4 rounded-lg border">
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Título</label>
                <input type="text" name="titulo" value="{{ request('titulo') }}" placeholder="Parte do título..." class="w-full border-gray-300 rounded-md shadow-sm p-2 text-sm border">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">ISBN</label>
                <input type="text" name="isbn" value="{{ request('isbn') }}" placeholder="ISBN exato..." class="w-full border-gray-300 rounded-md shadow-sm p-2 text-sm border">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Gênero</label>
                <select name="genero" class="w-full border-gray-300 rounded-md shadow-sm p-2 text-sm border">
                    <option value="">Todos os gêneros</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->GNRCODIGO }}" {{ request('genero') == $genero->GNRCODIGO ? 'selected' : '' }}>
                            {{ $genero->GNRNOME }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-center pt-5">
                <label class="flex items-center text-sm text-gray-700 cursor-pointer">
                    <input type="checkbox" name="disponivel" value="1" {{ request('disponivel') ? 'checked' : '' }} class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 mr-2">
                    Somente disponíveis
                </label>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="w-full bg-indigo-600 text-white rounded-md px-4 py-2 text-sm font-semibold hover:bg-indigo-700">Filtrar</button>
                <a href="{{ route('livros.index') }}" class="bg-gray-200 text-gray-700 rounded-md px-3 py-2 text-sm font-semibold hover:bg-gray-300 text-center">Limpar</a>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Título</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISBN</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Autores</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gêneros</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($livrosList as $livro)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('livros.show', $livro->LVRCODIGO) }}" class="text-indigo-600 font-semibold hover:underline">
                                    {{ $livro->LVRTITULO }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $livro->LVRISBN ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $livro->autores->pluck('AUTNOME')->join(', ') ?: 'Nenhum autor' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $livro->generos->pluck('GNRNOME')->join(', ') ?: 'Nenhum gênero' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">Nenhum livro cadastrado ou encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $livrosList->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>
