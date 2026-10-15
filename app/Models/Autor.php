<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model da tabela AUTORES.
 */
class Autor extends Model
{
    /**
     * Nome da tabela no banco.
     */
    protected $table = 'AUTORES';

    /**
     * Chave primária da tabela.
     */
    protected $primaryKey = 'AUTCODIGO';

    /**
     * A tabela não possui created_at e updated_at.
     */
    public $timestamps = false;

    /**
     * Colunas que podem ser preenchidas em massa.
     */
    protected $fillable = [
        'AUTNOME',
        'AUTPSEUDONIMO',
        'AUTBIOGRAFIA',
        'AUTPAISNASC',
    ];
}
