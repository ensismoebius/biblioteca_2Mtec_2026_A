<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Livro extends Model
{
    use HasFactory;

    protected $table = 'LIVROS';

    protected $primaryKey = 'LVRCODIGO';

    public $timestamps = false;

    protected $fillable = [
        'LVRTITULO',
        'LVRISBN',
        'LVREDICAO',
        'LVRDTPUBLIC',
        'LVRSINOPSE',
        'LVRFAIXAETARIA',
    ];

    protected $casts = [
        'LVRDTPUBLIC' => 'date',
        'LVRFAIXAETARIA' => 'integer',
    ];

    // Relacionamento N:N com Gêneros via CLASSIFICACAO (com withPivot)
    public function generos()
    {
        return $this->belongsToMany(
            Genero::class,
            'CLASSIFICACAO',
            'CLSLIVRO',
            'CLSGENERO'
        )->withPivot('CLSPRINCIPAL');
    }

    // Relacionamento N:N com Autores via AUTORIA (com withPivot)
    public function autores()
    {
        return $this->belongsToMany(
            Autor::class,
            'AUTORIA',
            'ATRLIVRO',
            'ATRAUTOR'
        )->withPivot('ATRPRINCIPAL');
    }

    // Relacionamento 1:N com Exemplares
    public function exemplares()
    {
        return $this->hasMany(Exemplar::class, 'EXMLIVRO', 'LVRCODIGO');
    }

    // Método para retornar o autor principal
    public function autorPrincipal()
    {
        return $this->autores()->wherePivot('ATRPRINCIPAL', true)->first();
    }

    // Método para retornar o gênero principal
    public function generoPrincipal()
    {
        return $this->generos()->wherePivot('CLSPRINCIPAL', true)->first();
    }
}
