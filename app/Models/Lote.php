<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $table = 'batches';
    protected $fillable = ['batchNumber']; // añade más columnas si es necesario
}
