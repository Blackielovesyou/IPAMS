<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAccessDetails extends Model
{
    use HasFactory;
    public $table = 'module_access_details';
    
    protected $fillable = [
        'code',
        'name',
        'description',
    ];
}
