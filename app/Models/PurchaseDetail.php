<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchaseDetail extends Model
{
    protected $table = 'purchase_details';

    protected $fillable = [
        'purchase_id', 
        'product_presentation_id', 
        'batch_id', 
        'product_id', 
        'purchase_price', 
        'sale_price', 
        'stock',
        'amount_total'
    ];

    // Relación con el Producto
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // Relación con la Presentación
    public function productPresentation()
    {
        return $this->belongsTo(ProductPresentation::class, 'product_presentation_id');
    }

    // Relación con el Lote
    public function batch()
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    // Relación con la Compra
    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }
}