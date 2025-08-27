<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductPresentation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
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
