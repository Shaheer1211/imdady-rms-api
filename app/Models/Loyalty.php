<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    use HasFactory;

    protected $table = 'loyalty';

    protected $fillable = [
        'name',
        'convert_points',
        'per_price',
        'percentage_order_amount',
        'minimum_point',
        'company_id',
        'status',
    ];
}
