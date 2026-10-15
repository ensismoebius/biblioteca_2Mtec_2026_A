<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model da tabela GENEROS.
 */
class Genero extends Model
{
    /**
     * Nome da tabela no banco.
     */
    protected $table = 'GENEROS';

    /**
     * Chave primária da tabela.
     */
    protected $primaryKey = 'GNRCODIGO';

    /**
     * A tabela não possui created_at e updated_at.
     */
    public $timestamps = false;

    /**
     * Colunas que podem ser preenchidas em massa.
     */
    protected $fillable = ['GNRNOME'];
}
