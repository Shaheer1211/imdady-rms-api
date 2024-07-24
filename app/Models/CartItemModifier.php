<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItemModifier extends Model
{
    use HasFactory;
    protected $table = 'cart_item_modifier';
    protected $fillable = [
        'id',
        'cart_id',
        'modifier_id',
        'quantity',
        'del_status',
        'created_at',
        'updated_at'
    ];
}
