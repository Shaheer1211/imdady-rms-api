<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMenus extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'name_arabic',
        'add_port_by_product',
        'category_id',
        'sub_category_id',
        'is_discount',
        'discount_amount',
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
        'status',
        'is_new',
        'is_tax_fix',
        'created_at',
        'updated_at',
        'del_status',
    ];

    public function category()
    {
        return $this->belongsTo(FoodMenuCategories::class, 'category_id');
    }

    public function subCategory()
    {
        return $this->belongsTo(FoodMenuSubCategories::class, 'sub_category_id');
    }

    public function vat()
    {
        return $this->belongsTo(Vats::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'outlet_id');
    }

    public function ingredients()
    {
        return $this->hasMany(FoodMenuIngredient::class, 'food_menu_id');
    }

    public function modifiers()
    {
        return $this->hasMany(FoodMenuModifiers::class, 'food_menu_id');
    }
}

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class FoodMenus extends Model
// {
//     use HasFactory;

//     protected $fillable = [
//         "id",
//         'code',
//         'name',
//         'name_arabic',
//         'add_port_by_product',
//         'category_id',
//         'sub_category_id',
//         'is_discount',
//         'discount_amount',
//         'description',
//         'sale_price',
//         'hunger_station_price',
//         'jahiz_price',
//         'tax_method',
//         'kot_print',
//         'is_vendor',
//         'vendor_name',
//         'vat_id',
//         'user_id',
//         'outlet_id',
//         'photo',
//         'veg_item',
//         'beverage_item',
//         'bar_item',
//         'stock',
//         'is_new',
//         'is_tax_fix',
//         'created_at',
//         'updated_at',
//         'del_status',
//     ];
// }
