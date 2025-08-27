<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    //
    protected $fillable = [
        'name',
        'usage',
        'description',
        'category_id',
        'laboratory_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratory::class);
    }

    public function productPresentations()
    {
        return $this->hasMany(ProductPresentation::class);
    }

    public function batches()
    {
        return $this->hasManyThrough(Batch::class, ProductPresentation::class);
    }
}
