<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMenus extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        'code',
        'name',
        'name_arabic',
        'add_port_by_product',
        'category_id',
        'sub_category_id',
        'is_deals',
        'deal_items_and_qty',
        'description',
        'sale_price',
        'hunger_station_price',
        'jahiz_price',
        'tax_method',
        'kot_print',
        'is_vendor',
        'vendor_name',
        'vat_id',
        'user_id',
        'outlet_id',
        'photo',
        'veg_item',
        'beverage_item',
        'bar_item',
        'stock',
        'is_new',
        'is_tax_fix',
        'created_at',
        'updated_at',
        'del_status',
    ];
}
