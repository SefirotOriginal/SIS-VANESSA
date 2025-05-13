<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Laboratorio extends Model
{
    protected $table = 'laboratories';
    protected $fillable = ['name']; // añade más columnas si es necesario
}
