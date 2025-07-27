<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //

    protected $fillable = [
        'name',
        'type',
        'description',
    ];
    protected $table = 'categories';
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function laboratories()
    {
        return $this->hasMany(Laboratory::class);
    }
}
