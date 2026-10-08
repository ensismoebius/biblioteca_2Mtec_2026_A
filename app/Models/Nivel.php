<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model do nível de acesso de um usuário.
 */
class Nivel extends Model
{
    protected $table = 'NIVEIS';

    protected $primaryKey = 'NVLCODIGO';

    public $timestamps = false;

    protected $fillable = ['NVLNOME'];

    /**
     * Usuários vinculados a este nível.
     */
    public function usuarios()
    {
        return $this->hasMany(Usuario::class, 'USRNIVEL', 'NVLCODIGO');
    }
}
