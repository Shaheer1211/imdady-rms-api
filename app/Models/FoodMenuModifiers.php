<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMenuModifiers extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'food_menu_id',
        'modifier_id',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status',
    ];
}
