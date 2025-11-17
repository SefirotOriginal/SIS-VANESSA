<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VentaDetalle extends Model
{
    use HasFactory;
    protected $table = 'venta_detalles';
    protected $fillable = [
        'venta_id',
        'product_presentation_id',
        'quantity',
        'price',
        'subtotal',
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