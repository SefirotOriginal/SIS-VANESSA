<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use App\Models\CashCuts;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class PivotCashCuts extends Pivot
{
    use SoftDeletes;
    protected $table = 'cash_cut_has_sales_has_users';
    public $incrementing = true;
    
    protected $fillable = [
        'cash_cut_id',
        'sale_id',
        'user_id',
    ];
    public function cashCut()
    {
        return $this->belongsTo(CashCuts::class, 'cash_cut_id');
    }
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');    
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
