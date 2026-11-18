<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Livros - Acervo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tela-pagina">
    <div class="tabela-container">
            <h1 class="titulo-pagina">Lista de Livros</h1>

        <x-tabela>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Autores</th>
                    <th>Gêneros</th>
                </tr>
            </thead>
            <tbody>
                @forelse($livrosList as $livro)
                    <tr>
                        <td>
                            <a href="{{ route('livros.show', $livro->LVRCODIGO) }}" class="link">
                                {{ $livro->LVRTITULO }}
                            </a>
                        </td>
                        <td>
                            {{ $livro->autores->pluck('AUTNOME')->join(', ') ?: 'Nenhum autor' }}
                        </td>
                        <td>
                            {{ $livro->generos->pluck('GNRNOME')->join(', ') ?: 'Nenhum gênero' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4">Nenhum livro cadastrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-tabela>
    </div>
</body>
</html>
