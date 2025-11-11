<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;


class ProductPresentation extends Model
{
    use HasFactory , SoftDeletes;
    protected $fillable = [
        'product_id',
        'presentation_id',
        'bar_code',
        'content',
        'formula',
        'purchase_price',
        'sale_price',
    ];

    /**
     * Get the product that owns the presentation.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the presentation type.
     */
    public function presentation()
    {
        return $this->belongsTo(Presentation::class);
    }

    /**
     * Get the batches associated with the product presentation.
     */
    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
