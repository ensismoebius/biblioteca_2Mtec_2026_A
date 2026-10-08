<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Representa um cliente cadastrado no sistema.
 */
class Cliente extends Model
{
    // nome da tabela no banco de dados
    protected $table = 'CLIENTES';

    // nome da chave primária no banco de dados
    protected $primaryKey = 'CLICODIGO';

    // o diagrama não tem created_at/updated_at
    public $timestamps = false;

    // campos que podem ser preenchidos em massa
    protected $fillable = [
        'CLINOME',
        'CLICPF',
        'CLITELEFONE',
        'CLIEMAIL',
        'CLIDTNASC',
        'CLIDTCAD'
    ];

    // define o tipo de dado para as datas
    protected $casts = [
        'CLIDTNASC' => 'date',
        'CLIDTCAD' => 'date'
    ];

    /**
     * Retorna os empréstimos associados ao cliente.
     */
    public function emprestimos()
    {
        return $this->hasMany(
            Emprestimo::class,
            'EMPCLIENTE',
            'CLICODIGO'
        );
    }

    /**
     * Calcula e retorna a idade do cliente com base na data de nascimento.
     */
    public function getIdadeAttribute()
    {
        if ($this->CLIDTNASC) {
            $birthDate = new \DateTime($this->CLIDTNASC);
            $today = new \DateTime();

            return $today->diff($birthDate)->y;
        }

        return null;
    }
}
