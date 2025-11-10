<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
// SoftDeletes


class Presentation extends Model
{
    use SoftDeletes;

    //
    protected $fillable = [
        'name',
        'description',
    ];

    protected $table = 'presentations';

}
