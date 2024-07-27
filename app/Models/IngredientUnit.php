<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'unit_name',
        'unit_value',
        'description',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];

    public function ingredient() {
        return $this->hasMany(Ingredient::class, 'unit_id');
    }
}
