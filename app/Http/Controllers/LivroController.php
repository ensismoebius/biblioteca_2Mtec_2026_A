<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Livro;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controla as requisições referentes aos livros do acervo,
 * incluindo listagem com filtros, paginação e cadastro.
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

        $livrosList = $query->paginate(10);
        $generos = Genero::all();

        return view('livros.index', compact('livrosList', 'generos'));
    }

    /**
     * Exibe o formulário de cadastro de um novo livro.
     */
    public function create(): View
    {
        $autores = Autor::all();
        $generos = Genero::all();

        return view('livros.create', compact('autores', 'generos'));
    }
}
