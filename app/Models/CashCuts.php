<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class CashCuts extends Model
{
    //
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'start_time',
        'end_time',
        'initial_amount',
        'real_amount',
        'final_amount',
        'diference',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function sales()
    {
        return $this->belongsToMany(
            Sale::class,
            'cash_cut_has_sales_has_users',
            'cash_cut_id',
            'sale_id'
        )
        ->using(PivotCashCuts::class)
        ->withPivot('user_id')
        ->withTimestamps();
    }

    public function purchases()
    {
        return $this->belongsToMany(
            Purchase::class, 
            'cash_cut_has_purchases',
            'cash_cut_id', 
            'purchase_id'
        )->withTimestamps();
    }
}
