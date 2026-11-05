<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

/**
 * Controller responsável pelo gerenciamento de autores.
 */
class AutorController extends Controller
{
    /**
     * Exibe a listagem dos autores com paginação de 15 registros.
     */
    public function index()
    {
        $autores = Autor::paginate(15);

        return view('autores.index', compact('autores'));
    }

    /**
     * Exibe o formulário de criação de um novo autor.
     */
    public function create()
    {
        return view('autores.create');
    }

    /**
     * Salva o novo autor com os dados validados.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'AUTNOME' => 'required|string|max:100',
        ]);

        Autor::create($validated);

        return redirect()->route('autores.index')
            ->with('success', 'Autor cadastrado com sucesso!');
    }

    /**
     * Exibe um autor específico.
     */
    public function show(Autor $autore)
    {
        return view('autores.show', ['autor' => $autore]);
    }

    /**
     * Exibe o formulário de edição de um autor.
     */
    public function edit(Autor $autore)
    {
        return view('autores.edit', ['autor' => $autore]);
    }

    /**
     * Atualiza o autor específico salvando os dados validados.
     */
    public function update(Request $request, Autor $autore)
    {
        $validated = $request->validate([
            'AUTNOME' => 'required|string|max:100',
        ]);

        $autore->update($validated);

        return redirect()->route('autores.index')
            ->with('success', 'Autor atualizado com sucesso!');
    }

    /**
     * Remove o autor e retorna com mensagem flash de confirmação.
     */
    public function destroy(Autor $autore)
    {
        $autore->delete();

        return redirect()->route('autores.index')
            ->with('success', 'Autor excluído com sucesso!');
    }
}
