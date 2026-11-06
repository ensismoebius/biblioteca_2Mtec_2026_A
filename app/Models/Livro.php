<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model responsável pelos dados dos livros.
 */
class Livro extends Model
{
    //
    protected $table = 'LIVROS';

    protected $primaryKey = 'LVRCODIGO';

    protected $fillable = [
        'LVRTITULO',
        'LVRISBN',
        'LVREDICAO',
        'LVRDTPUBLIC',
        'LVRSINOPSE',
        'LVRFAIXAETARIA',
    ];

    /**
     * Define os tipos dos atributos do livro.
     */
    protected function casts(): array
    {
        return [
            'LVREDICAO' => 'integer',
            'LVRDTPUBLIC' => 'date',
            'LVRFAIXAETARIA' => 'integer',
        ];
    }
}
