<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genero extends Model
{
    //
    
    protected $table = 'generos';
    protected $fillable = ['GNRNOME'];
    public $timestamps = false;
}
