<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Genero;
use App\Models\Livro;
use Illuminate\Http\Request;

/**
 * Controller responsável pela gestão e operações CRUD de livros.
 */
class LivroController extends Controller
{
    /**
     * Exibe a listagem de livros ordenada por título e sem N+1.
     */
    public function index()
    {
        $livrosList = Livro::with(['autores', 'generos'])
            ->orderBy('LVRTITULO', 'asc')
            ->get();

        return view('livros.index', compact('livrosList'));
    }

    /**
     * Mostra o formulário para criar um novo livro, enviando autores e gêneros para os selects.
     */
    public function create()
    {
        $autores = Autor::all();
        $generos = Genero::all();

        return view('livros.create', compact('autores', 'generos'));
    }

    /**
     * Armazena um novo livro na base de dados.
     */
    public function store(Request $request)
    {
        // Lógica de validação e salvamento do livro e relações conforme o seu projeto
        $livro = Livro::create($request->all());

        if ($request->has('autores')) {
            $livro->autores()->sync($request->input('autores'));
        }

        if ($request->has('generos')) {
            $livro->generos()->sync($request->input('generos'));
        }

        return redirect()->route('livros.index')->with('success', 'Livro criado com sucesso!');
    }

    /**
     * Exibe os detalhes de um livro específico, incluindo a contagem de exemplares disponíveis.
     */
    public function show($id)
    {
        $livro = Livro::with(['autores', 'generos', 'exemplares'])->findOrFail($id);

        // Conta quantos exemplares estão disponíveis
        $exemplaresDisponiveisCount = $livro->exemplares->where('EXMDISPONIVEL', true)->count();

        return view('livros.show', compact('livro', 'exemplaresDisponiveisCount'));
    }

    /**
     * Mostra o formulário de edição de um livro existente, enviando autores e gêneros.
     */
    public function edit($id)
    {
        $livro = Livro::with(['autores', 'generos'])->findOrFail($id);
        $autores = Autor::all();
        $generos = Genero::all();

        return view('livros.edit', compact('livro', 'autores', 'generos'));
    }

    /**
     * Atualiza um livro existente na base de dados.
     */
    public function update(Request $request, $id)
    {
        $livro = Livro::findOrFail($id);
        $livro->update($request->all());

        if ($request->has('autores')) {
            $livro->autores()->sync($request->input('autores'));
        }

        if ($request->has('generos')) {
            $livro->generos()->sync($request->input('generos'));
        }

        return redirect()->route('livros.index')->with('success', 'Livro atualizado com sucesso!');
    }

    /**
     * Remove um livro da base de dados.
     */
    public function destroy($id)
    {
        $livro = Livro::findOrFail($id);
        $livro->delete();

        return redirect()->route('livros.index')->with('success', 'Livro removido com sucesso!');
    }
}
