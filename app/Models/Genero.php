<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * tabela que guarda os generos dos livros
 */
class Genero extends Model
{
    //

    protected $table = 'generos';

    protected $fillable = ['GNRNOME'];

    public $timestamps = false;
}
