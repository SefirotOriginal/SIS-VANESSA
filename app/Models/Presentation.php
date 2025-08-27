<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presentation extends Model
{
    //
    protected $fillable = [
        'name',
        'description',
    ];

    protected $table = 'presentations';
    
}
