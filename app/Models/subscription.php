<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class subscription extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        'heading',
        'delivery_charges',
        'is_delivery_charge',
        'is_meal_type',
        "item_qty",
        'cat_discount',
        'category',
        'category_meal',
        'details',
        "amount",
        'full_amount',
        'company_id	',
        'is_company_sub	',
        'expire_days',
        "del_status",
       
    ];
}
