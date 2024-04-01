<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IngredientCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'category_name',
        'description',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];
}
