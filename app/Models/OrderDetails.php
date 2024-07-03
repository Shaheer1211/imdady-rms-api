<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'food_menu_id',
        'single_discount',
        'qty',
        'menu_unit_price',
        'menu_price_with_discount',
        'menu_unit_price_with_vat',
        'menu_vat_percentage',
        'menu_taxes',
        'menu_discount_value',
        'discount_type',
        'discount_amount',
        'menu_note',
        'item_type',
        'cooking_status',
        'cooking_start_time',
        'cooking_end_time',
        'order_id',
        'del_status',
        'created_at',
        'updated_at',
    ];
}
