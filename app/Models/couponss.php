<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class couponss extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'name',
        'code',
        'minimum_purchase_price',
        'dis_type',
        'expired_date',
        'status',
        'discount_amount',
        'del_status',
    ];

    protected $dates = ['deleted_at'];
}
