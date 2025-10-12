<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditTrailModel extends Model
{
    use HasFactory;
    public $table = "audit_trail";
    
    protected $fillable = [
        'userID',
        'user_email',
        'action',
        'description',
        'ip_address'
    ];
}
