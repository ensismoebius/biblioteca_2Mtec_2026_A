<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Livros - Acervo</title>
    <style>
        /* RESET & ESTRUTURA GERAL */
        body { font-family: system-ui, -apple-system, sans-serif; margin: 0; padding: 30px; background: #f4f5f7; color: #1f2937; }
        .container { max-width: 1100px; margin: auto; background: #ffffff; padding: 30px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { font-size: 1.5rem; margin-top: 0; margin-bottom: 20px; font-weight: 600; color: #111; }

        /* FORMULÁRIO DE FILTROS */
        form { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; align-items: end; margin-bottom: 25px; background: #f9fafb; padding: 20px; border-radius: 6px; border: 1px solid #e5e7eb; }
        .form-group { display: flex; flex-direction: column; gap: 5px; }
        .form-group label { font-size: 0.85rem; font-weight: 500; color: #4b5563; }
        .form-group input[type="text"], .form-group select { padding: 8px 10px; border: 1px solid #d1d5db; border-radius: 4px; font-size: 0.9rem; }
        .checkbox-group { display: flex; align-items: center; gap: 8px; height: 38px; font-size: 0.85rem; color: #4b5563; cursor: pointer; }

        /* BOTÕES E AÇÕES */
        .actions { display: flex; gap: 10px; align-items: center; }
        .btn-submit { background: #2563eb; color: white; border: none; padding: 9px 16px; border-radius: 4px; font-size: 0.9rem; font-weight: 500; cursor: pointer; }
        .btn-submit:hover { background: #1d4ed8; }
        .btn-clear { color: #4b5563; font-size: 0.9rem; text-decoration: none; padding: 9px 12px; }
        .btn-clear:hover { text-decoration: underline; color: #111; }

        /* TABELA DE DADOS */
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 12px 15px; border-bottom: 1px solid #e5e7eb; text-align: left; font-size: 0.9rem; }
        th { background: #f9fafb; font-weight: 600; color: #374151; }
        tr:hover td { background: #fafafa; }

        /* LINKS */
        a { color: #2563eb; text-decoration: none; }
        a:hover { text-decoration: underline; }

        /* PAGINAÇÃO */
        .pagination { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; font-size: 0.9rem; color: #4b5563; }
        .pagination nav div:first-child { display: none; }
        .pagination svg { width: 20px; height: 20px; display: inline-block; vertical-align: middle; }
        .pagination span[aria-current="page"] span,
        .pagination a { padding: 6px 12px; border: 1px solid #d1d5db; border-radius: 4px; margin: 0 2px; background: #fff; color: #374151; }
        .pagination span[aria-current="page"] span { background: #2563eb; color: #fff; border-color: #2563eb; }
        .pagination a:hover { background: #f3f4f6; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista de Livros</h1>

        <!-- Formulário de Busca e Filtros (Issue #40) -->
        <form method="GET" action="{{ route('livros.index') }}">
            <div class="form-group">
                <x-campo name="titulo" label="Título" :value="request('titulo')" ajuda="" />
            </div>

            <div class="form-group">
                <x-campo name="isbn" label="ISBN" :value="request('isbn')" ajuda="" />
            </div>

            <div class="form-group">
                <label for="genero">Gênero</label>
                <select id="genero" name="genero">
                    <option value="">Todos os gêneros</option>
                    @foreach($generos as $genero)
                        <option value="{{ $genero->GNRCODIGO }}" {{ request('genero') == $genero->GNRCODIGO ? 'selected' : '' }}>
                            {{ $genero->GNRNOME }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="checkbox-group">
                    <input type="checkbox" name="disponivel" value="1" {{ request('disponivel') ? 'checked' : '' }}>
                    Somente disponíveis
                </label>
            </div>

            <div class="actions">
                <x-botao variante="primario" type="submit" class="btn-submit">Filtrar</x-botao>
                <a href="{{ route('livros.index') }}" class="btn-clear">Limpar</a>
            </div>
        </form>

        <!-- Tabela reutilizável padrão x-tabela -->
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
                            <a href="{{ route('livros.show', $livro->LVRCODIGO) }}">
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
                        <td colspan="4" style="text-align: center; color: #6b7280; padding: 25px;">Nenhum livro cadastrado ou encontrado.</td>
                    </tr>
                @endforelse
            </tbody>
        </x-tabela>

        <!-- Links de Paginação -->
        <div class="pagination">
            {{ $livrosList->withQueryString()->links() }}
        </div>
    </div>
</body>
</html>
