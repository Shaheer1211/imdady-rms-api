<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productdiscont extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        'product_id',
        'category_id',
        'start_date',
        'end_date',
        "dis_type",
        'use_discount',
        'discount_amount',
        'user_id',
        'outlet_id',
        "specific_customers",
        'multi_customer_id',
        'del_status	', 
    ];
}
