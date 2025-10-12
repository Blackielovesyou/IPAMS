<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;
    
    public $table = 'notifications';
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'type'
    ];
    
    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
