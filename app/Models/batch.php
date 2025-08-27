<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_presentation_id',
        'batch_number',
        'creation_date',
        'expiration_date',
        'stock',
        'min_stock',
        'max_stock',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'creation_date' => 'datetime',
        'expiration_date' => 'datetime',
    ];

    /**
     * Get the product presentation that owns the batch.
     */
    public function productPresentation()
    {
        return $this->belongsTo(ProductPresentation::class);
    }
}
