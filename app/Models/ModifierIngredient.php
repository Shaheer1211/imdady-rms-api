<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModifierIngredient extends Model
{
    use HasFactory;
    protected $table = 'modifiers_ingredient';
    protected $fillable = [
        'id',
        'modifier_id',
        'ingredient_id',
        'consumption',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
    ];
}
