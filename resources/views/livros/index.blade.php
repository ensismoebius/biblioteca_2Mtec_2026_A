<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo de Livros - Acervo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="tela-pagina">
    <div class="tabela-catalogo-container">
        <h1 class="titulo-pagina">Catálogo de Livros</h1>

        <form method="GET" action="{{ route('livros.index') }}" class="form-grid-filtros">
            <div class="form-grupo">
                <x-campo name="titulo" label="Título" :value="request('titulo')" ajuda="" />
            </div>

            <div class="form-grupo">
                <x-campo name="isbn" label="ISBN" :value="request('isbn')" ajuda="" />
            </div>

            <div class="form-grupo">
                <label for="genero" class="form-label">Gênero</label>
                <select id="genero" name="genero" class="form-input">
                    <option value="">Todos os gêneros</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->GNRCODIGO }}" {{ request('genero') == $genero->GNRCODIGO ? 'selected' : '' }}>
                            {{ $genero->GNRNOME }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="disponivel" value="1" {{ request('disponivel') ? 'checked' : '' }}>
                    Somente disponíveis
                </label>
            </div>

            <div class="acoes-formulario">
                <x-botao variante="primario" type="submit" class="btn-primario">Filtrar</x-botao>
                <a href="{{ route('livros.index') }}" class="link-padrao">Limpar</a>
            </div>
        </form>

        <x-tabela>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>ISBN</th>
                    <th>Autores</th>
                    <th>Gêneros</th>
                </tr>
            </thead>
            <tbody>
                @forelse($livrosList as $livro)
                    <tr>
                        <td>
                            <a href="{{ route('livros.show', $livro->LVRCODIGO) }}" class="link-padrao">
                                {{ $livro->LVRTITULO }}
                            </a>
                        </td>
                        <td>
                            {{ $livro->LVRISBN ?? 'N/A' }}
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
                        <td colspan="4" class="tabela-vazia">Nenhum livro cadastrado ou encontrado no catálogo.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-tabela>

        <div class="paginacao-container">
            {{ $livrosList->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>
