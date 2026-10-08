<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\LivroResource;
use App\Models\Livro;

/**
 * Controller responsável pelos endpoints de consulta de livros.
 */
class LivroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $livros = Livro::query()->paginate(10);

        return LivroResource::collection($livros);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $livro = Livro::findOrFail($id);

        return new LivroResource($livro);
    }
}
