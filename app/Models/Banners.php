<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banners extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'banner_image',
        'banner_name',
        'status',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];

}
