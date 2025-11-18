<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Sale extends Model
{
    use SoftDeletes;
    protected $fillable = [
        // 'user_id',
        // 'referenceNumber',
        // 'receiptType',
        // 'amountPayment',
        // 'amountTotal',
        // 'amountExchange',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details()
    {
        return $this->hasMany(DetailSale::class, 'sale_id', 'id');
    }
}
