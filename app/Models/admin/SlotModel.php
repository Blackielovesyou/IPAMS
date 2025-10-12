<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SlotModel extends Model
{
    use HasFactory;
    public $table = 'slot_db';
    
    protected $fillable = [
        'title',
        'description',
        'start',
        'end',
        'color',
        'slots',
        'virtual_slots'
    ];
}
