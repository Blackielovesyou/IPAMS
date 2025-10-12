<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockedAccountModel extends Model
{
    use HasFactory;
    public $table = 'blocked_account';
    protected $fillable = [
        'userID'
    ];
}
