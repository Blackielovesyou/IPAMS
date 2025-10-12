<?php

namespace App\Models\admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppInfoModel extends Model
{
    use HasFactory;
    public $table = 'app_info';
    
protected $fillable = [
    'logo',
    'app_name',
    'banner',
    'mission_vission',
    'guidelines',
    'terms_and_condition',
    'website',
    'facebook',
    'youtube',
    'contact',
    'about_us',
    'email',
    'address',
    'version_code',

    // Payment fields
    'payment_method',
    'payment_number',
    'payment_qr',
];

}
