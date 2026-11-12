<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Livro - Acervo</title>
    <style>
        /* RESET & ESTRUTURA GERAL */
        body { font-family: system-ui, -apple-system, sans-serif; margin: 0; padding: 30px; background: #f4f5f7; color: #1f2937; }
        .container { max-width: 800px; margin: auto; background: #ffffff; padding: 30px; border-radius: 6px; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        h1 { font-size: 1.5rem; margin-top: 0; margin-bottom: 20px; font-weight: 600; color: #111; }

        /* CONTEÚDO DOS DETALHES */
        .details-group { margin-bottom: 15px; font-size: 0.95rem; color: #4b5563; }
        .details-group strong { color: #1f2937; }

        /* BOTÃO DE VOLTAR */
        .actions { margin-top: 25px; }
        a { text-decoration: none; }
        .btn-back { display: inline-block; background: #e5e7eb; color: #374151; padding: 8px 16px; border-radius: 4px; font-size: 0.9rem; font-weight: 500; }
        .btn-back:hover { background: #d1d5db; }
    </style>
</head>
<body>
    <div class="container">
        <!-- Título do Livro -->
        <h1>{{ $livro->LVRTITULO }}</h1>

        <!-- Informações Detalhadas -->
        <div class="details-group">
            <strong>Editora:</strong> {{ $livro->LVREDITORA ?? 'N/A' }}
        </div>

        <div class="details-group">
            <strong>Exemplares Disponíveis:</strong> {{ $exemplaresDisponiveisCount ?? 0 }}
        </div>

        <!-- Botão de Retorno -->
        <div class="actions">
            <a href="{{ route('livros.index') }}">
                <x-botao variante="secundario" class="btn-back">← Voltar para a listagem</x-botao>
            </a>
        </div>
    </div>
</body>
</html>
