<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderModifierDetails extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'order_id',
        'order_details_id',
        'modifier_id',
        'qty',
        'sell_price',
        'vat',
        'del_status',
        'created_at',
        'updated_at',
    ];
}
