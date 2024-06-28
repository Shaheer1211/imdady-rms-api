<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMenuIngredient extends Model
{
    use HasFactory;
    protected $table = 'food_menu_ingredient';
    protected $fillable = [
        'id',
        'food_menu_id',
        'ingredient_id',
        'consumption',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
    ];
}
