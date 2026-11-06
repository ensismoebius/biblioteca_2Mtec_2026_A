<?php

namespace App\Http\Controllers;

use App\Models\Genero;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Controller do CRUD de gêneros.
 */
class GeneroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $generos = Genero::paginate(15);

        return view('generos.index', compact('generos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'GNRNOME' => ['required', 'string', 'min:3', 'max:30', 'unique:GENEROS,GNRNOME'],
        ]);

        Genero::create($dados);

        return redirect()->route('generos.index')->with('sucesso', 'Gênero cadastrado com sucesso.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Genero $genero)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Genero $genero)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Genero $genero)
    {
        $dados = $request->validate([
            'GNRNOME' => ['required', 'string', 'min:3', 'max:30', Rule::unique('GENEROS', 'GNRNOME')->ignore($genero)],
        ]);

        $genero->update($dados);

        return redirect()->route('generos.index')->with('sucesso', 'Gênero atualizado com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Genero $genero)
    {
        $genero->delete();

        return redirect()->route('generos.index')->with('sucesso', 'Gênero excluído com sucesso.');
    }
}
