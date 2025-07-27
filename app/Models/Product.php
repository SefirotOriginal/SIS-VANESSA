<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    protected $fillable = [
        'name',
        'bar_code',
        'usage',
        'content',
        'formula',
        'description',
        'presentation',
        'status',
        'purchase_price',
        'sale_price',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }
}
