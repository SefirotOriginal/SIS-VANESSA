<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Usuario;

class Venta extends Model
{
    use HasFactory;
    protected $table = 'sales';
    protected $fillable = [
        'id',            
        'referenceNumber',       
        'receiptType',
        'amountPayment', 
        'amountExchange',  
        'amountTotal',
        'user_id',    
        'created_at',
        'updated_at', 
        'deleted_at', 
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'user_id');
    }
}
