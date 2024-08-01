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
        'name',
        'name_arabic',
        'is_tax_fix',
        'qty',
        'menu_unit_price',
        'menu_price_with_discount',
        'menu_unit_price_with_vat',
        'menu_taxes',
        'discount_type',
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

    public function foodMenu()
    {
        return $this->belongsTo(FoodMenus::class, 'food_menu_id');
    }

    public function modifiers()
    {
        return $this->belongsToMany(Modifiers::class, 'order_modifier_details', 'order_details_id', 'modifier_id')
                    ->withPivot('qty');
    }

    protected $casts = [
        'menu_taxes' => 'array', // Cast to array
    ];
}
