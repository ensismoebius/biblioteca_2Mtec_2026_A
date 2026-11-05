<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Representa um livro do acervo (tabela LIVROS).
 *
 * Um livro pode ter vários gêneros e autores (relações N:N) e vários
 * exemplares físicos (relação 1:N).
 */
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

    /**
     * Gêneros do livro (N:N via CLASSIFICACAO).
     *
     * O campo pivot CLSPRINCIPAL indica se o gênero é o principal.
     */
    public function generos(): BelongsToMany
    {
        return $this->belongsToMany(
            Genero::class,
            'CLASSIFICACAO',
            'CLSLIVRO',
            'CLSGENERO'
        )->withPivot('CLSPRINCIPAL');
    }

    /**
     * Autores do livro (N:N via AUTORIA).
     *
     * O campo pivot ATRPRINCIPAL indica se o autor é o principal.
     */
    public function autores(): BelongsToMany
    {
        return $this->belongsToMany(
            Autor::class,
            'AUTORIA',
            'ATRLIVRO',
            'ATRAUTOR'
        )->withPivot('ATRPRINCIPAL');
    }

    /**
     * Exemplares físicos deste livro (1:N).
     */
    public function exemplares(): HasMany
    {
        return $this->hasMany(Exemplar::class, 'EXMLIVRO', 'LVRCODIGO');
    }

    /**
     * Retorna o autor marcado como principal do livro.
     * Autor principal, ou null se não houver nenhum.
     */
    public function autorPrincipal(): ?Autor
    {
        return $this->autores()->wherePivot('ATRPRINCIPAL', true)->first();
    }

    /**
     * Retorna o gênero marcado como principal do livro.
     * Gênero principal, ou null se não houver nenhum.
     */
    public function generoPrincipal(): ?Genero
    {
        return $this->generos()->wherePivot('CLSPRINCIPAL', true)->first();
    }
}
