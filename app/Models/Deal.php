<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deal extends Model
{
    use HasFactory;

    protected $table = 'deal';
    protected $fillable = [
        'id',
        'code',
        'name',
        'name_arabic',
        'category_id',
        'sale_price',
        'description',
        'is_discount',
        'discount_percentage',
        'hunger_station_price',
        'jahiz_price',
        'tax_method',
        'kot_print',
        'vat_id',
        'photo',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status',
    ];
}
