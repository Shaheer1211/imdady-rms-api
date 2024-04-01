<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'code',
        'name',
        'category_id',
        'purchase_price',
        'vat_percentage',
        'tax_method',
        'ing_vat',
        'total_amount',
        'alert_quantity',
        'unit_id',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];
}
