<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $table = 'purchases';

    protected $fillable = [
        'reference_number', 
        'receipt_type', 
        'amountTotal', 
        'user_id', 
        'provider_id'
    ];

    // Se relaciona cada compra con un proovedor, con sus detalles y el usuario
    public function provider()
    {
        return $this->belongsTo(Provider::class, 'provider_id');
    }

    public function details()
    {
        return $this->hasMany(PurchaseDetail::class, 'purchase_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}