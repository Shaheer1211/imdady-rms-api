<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodMenuCategories extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_name',
        'cat_name_arabic',
        'description',
        'cat_image',
        'cat_banner',
        'web_status',
        'subscriptions_status',
        'status',
        'is_subscription',
        'add_port',
        'user_id',
        'outlet_id',
        'is_sub_cat',
        'is_priority',
        'del_status'
    ];

    protected $dates = ['deleted_at'];
}
