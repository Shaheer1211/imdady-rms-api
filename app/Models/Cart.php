<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "session_id",
        "outlet_id",
        "item_id",
        "deal_id",
        "quantity"
    ];

    public function foodMenu()
    {
        return $this->belongsTo(FoodMenus::class, 'item_id');
    }

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function cartItemModifiers()
    {
        return $this->hasMany(CartItemModifier::class, 'cart_id');
    }
}
