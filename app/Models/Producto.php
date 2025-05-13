<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categoria;
use App\Models\Laboratorio;
use App\Models\lote;

class Producto extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'id',            
        'barCode',       
        'name',
        'description',   
        'currentStock',  
        'mintStock',
        'maxStock',
        'purchasePrice',
        'salePrice',
        'category_id',
        'batch_id',
        'laboratory_id', 
        'created_at',    
        'updated_at',
        'deleted_at',    
    ];

    public function category()
    {
        return $this->belongsTo(Categoria::class);
    }

    public function laboratory()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function batch()
    {
        return $this->belongsTo(Lote::class);
    }
}
