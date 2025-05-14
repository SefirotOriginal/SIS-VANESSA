<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    use HasFactory;
    protected $table = 'users';
    protected $fillable = [
        'id',            
        'name',       
        'email',
        'email_verified_at', 
        'password',  
        'remember_token',
        'created_at',    
        'updated_at',  
    ];
}
