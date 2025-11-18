<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailSale extends Model
{
    use SoftDeletes;

    protected $table = 'sales_details';
    protected $fillable = [
        'id',
        'sale_id',
        'salePrice',
        'product_id',
        'product_presentation_id',
        'quantityProduct',
        'subTotal',

    ];
    public function productPresentation()
    {
        /*
        El segundo argumento 'product_presentation_id' es la llave foránea
        que usa para encontrar la presentación
        */
        return $this->belongsTo(ProductPresentation::class, 'product_presentation_id');
    }
}
