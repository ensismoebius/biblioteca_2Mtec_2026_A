<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Representa um empréstimo registrado no sistema.
 */
class Emprestimo extends Model
{
    // nome da tabela no banco de dados
    protected $table = 'EMPRESTIMOS';

    // nome da chave primária no banco de dados
    protected $primaryKey = 'EMPCODIGO';

    // o diagrama não tem created_at/updated_at
    public $timestamps = false;

    // campos que podem ser preenchidos em massa
    protected $fillable = [
        'EMPLIVRO',
        'EMPUSUARIO',
        'EMPCLIENTE',
        'EMPDTEMPR',
        'EMPDTDEVOL'
    ];

    // define o tipo de dado para as datas
    protected $casts = [
        'EMPDTEMPR' => 'date',
        'EMPDTDEVOL' => 'date'
    ];

    /**
     * Retorna o cliente associado ao empréstimo.
     */
    public function cliente()
    {
        return $this->belongsTo(
            Cliente::class,
            'EMPCLIENTE',
            'CLICODIGO'
        );
    }

    /**
     * Retorna o usuário responsável pelo empréstimo.
     */
    public function usuario()
    {
        return $this->belongsTo(
            Usuario::class,
            'EMPUSUARIO',
            'USRCODIGO'
        );
    }

    /**
     * Retorna o livro associado ao empréstimo.
     */
    public function livro()
    {
        return $this->belongsTo(
            Livro::class,
            'EMPLIVRO',
            'LVRCODIGO'
        );
    }

    /**
     * Filtra apenas os empréstimos que ainda não foram devolvidos.
     */
    public function scopeAtivos($query)
    {
        return $query->whereNull('EMPDTDEVOL');
    }

    /**
     * Filtra apenas os empréstimos que já foram devolvidos.
     */
    public function scopeDevolvidos($query)
    {
        return $query->whereNotNull('EMPDTDEVOL');
    }
}
