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
}
