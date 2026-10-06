<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    //
    protected $table = 'NIVEIS';
    protected $fillable = ['NVLNOME'];
    protected $primaryKey = 'NVLCODIGO'; 
    public $timestamps = true;
}
