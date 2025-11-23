<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Sale;
use App\Models\ProductPresentation;
use App\Models\Product;
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
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
