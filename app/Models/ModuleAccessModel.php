<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleAccessModel extends Model
{
    use HasFactory;
    public $table = 'module_access_data';
    
    protected $fillable = [
        'userID',
        'module_code',
        'module_name',
        'module_description',
    ];
}
