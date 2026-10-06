<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    //
    protected $table = 'niveis';
    protected $fillable = ['NVLNOME'];
    public $timestamps = false;
}
