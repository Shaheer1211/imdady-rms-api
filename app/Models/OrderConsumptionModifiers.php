<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderConsumptionModifiers extends Model
{
    use HasFactory;
    protected $table = [
        'id',
        'ingredient_id',
        'consumption',
        'order_id',
        'modifier_id',
        'del_status',
    ];
}
