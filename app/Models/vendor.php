<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class vendor extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'vendor_name',
        'phone',
        'description',
        'user_id ',
        'outlet_id',
        'del_status'
    ];
}
