<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class user_verification extends Model
{
    use HasFactory;
    
    public $table = 'user_verification';
    
    protected $fillable = [
        'userID',
        'id_type',
        'id_file_url',
        'userStatus'
    ];
}
