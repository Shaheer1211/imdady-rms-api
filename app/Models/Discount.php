<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    protected $table = 'discounts';

    protected $fillable = [
        'product_id', 'category_id', 'start_date', 'end_date', 'dis_type',
        'use_discount', 'discount_amount', 'user_id', 'company_id',
        'specific_customers', 'multi_customer_id', 'del_status'
    ];

    protected $casts = [
        'multi_customer_id' => 'array', // Cast to array
    ];

    
    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'customer_discounts', 'discount_id', 'customer_id');
    }
}
