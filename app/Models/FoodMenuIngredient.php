<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMenuIngredient extends Model
{
    use HasFactory;

    protected $table = 'food_menu_ingredient'; // Corrected the typo here

    protected $fillable = [
        'food_menu_id',
        'ingredient_id',
        'consumption',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
    ];

    public function foodMenu()
    {
        return $this->belongsTo(FoodMenus::class, 'food_menu_id');
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class, 'ingredient_id');
    }
}
