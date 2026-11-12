<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controla as requisições referentes aos livros do acervo,
 * incluindo listagem com filtros, paginação e detalhes.
 */
class LivroController extends Controller
{
    /**
     * Exibe a listagem de livros aplicando os filtros de busca e paginação.
     */
    public function index(Request $request): View
    {
        $query = Livro::with(['autores', 'generos']);

        if ($request->filled('titulo')) {
            $query->titulo($request->input('titulo'));
        }

        if ($request->filled('isbn')) {
            $query->isbn($request->input('isbn'));
        }

        if ($request->filled('genero')) {
            $query->genero($request->input('genero'));
        }

        if ($request->boolean('disponivel')) {
            $query->disponivel();
        }

        $livrosList = $query->paginate(10)->withQueryString();
        $generos = Genero::all();

        return view('livros.index', compact('livrosList', 'generos'));
    }

    /**
     * Exibe os detalhes de um livro específico.
     */
    public function show($id): View
    {
        // Busca o livro pelo código legado ou retorna erro 404
        $livro = Livro::with(['autores', 'generos', 'exemplares'])
            ->where('LVRCODIGO', $id)
            ->firstOrFail();

        // Conta quantos exemplares estão disponíveis
        $exemplaresDisponiveisCount = $livro->exemplares()
            ->where('EXMDISPONIVEL', true)
            ->count();

        return view('livros.show', compact('livro', 'exemplaresDisponiveisCount'));
    }
}
