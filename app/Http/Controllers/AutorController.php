<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use Illuminate\Http\Request;

class AutorController extends Controller
{
    
     //exibe a listagem dos autores com paginação de 15 registros
    public function index()
    {
        $autores = Autor::paginate(15);
        return view('autores.index', compact('autores'));
    }

     //form de criação
    public function create()
    {
        return view('autores.create');
    }
    
     //salva o novo autor com os dados validados
    public function store(Request $request)
    {
        $validated = $request->validate([
            'AUTNOME' => 'required|string|max:100',
        ]);

        Autor::create($validated);

        return redirect()->route('autores.index')
            ->with('success', 'Autor cadastrado com sucesso!');
    }

     //exibe um autor específico
    public function show(Autor $autore)
    {
        return view('autores.show', ['autor' => $autore]);
    }

     //form de edição
    public function edit(Autor $autore)
    {
        return view('autores.edit', ['autor' => $autore]);
    }

     //atualiza o autor específico salvando os dados validados
    public function update(Request $request, Autor $autore)
    {
        $validated = $request->validate([
            'AUTNOME' => 'required|string|max:100',
        ]);

        $autore->update($validated);

        return redirect()->route('autores.index')
            ->with('success', 'Autor atualizado com sucesso!');
    }

     //remove o autor e retorna com mensagem flash de confirmação
    public function destroy(Autor $autore)
    {
        $autore->delete();

        return redirect()->route('autores.index')
            ->with('success', 'Autor excluído com sucesso!');
    }
}
