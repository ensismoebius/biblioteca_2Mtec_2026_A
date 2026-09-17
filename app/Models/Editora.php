<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Model de teste, limpo, para validar que o gate aprova código correto.
 */
class Editora extends Model
{
    protected $table = 'EDITORAS';
    protected $primaryKey = 'EDTCODIGO';
    public $timestamps = false;

    protected $fillable = ['EDTNOME'];
}
