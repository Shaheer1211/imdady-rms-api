<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodMenuSubCategories extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        "category_id",
        "sub_category_name",
        "sub_category_name_arabic",
        "description",
        "user_id",
        "outlet_id",
        "created_at",
        "updated_at",
        "del_status"
    ];
}
