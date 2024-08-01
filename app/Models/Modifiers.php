<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifiers extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'price',
        'description',
        'tax_method',
        'tax',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function ingredients()
    {
        return $this->hasManyThrough(Ingredient::class, ModifierIngredient::class, 'modifier_id', 'id', 'id', 'ingredient_id');
    }
    public function modifierIngredients()
{
    return $this->hasMany(ModifierIngredient::class, 'modifier_id');
}
}
