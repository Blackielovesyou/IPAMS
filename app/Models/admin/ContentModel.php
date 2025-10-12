<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContentModel extends Model
{
    use HasFactory;
    
    public $table = 'content_table';
}
