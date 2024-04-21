<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AllSales extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'name',
        'sit_capacity',
        'position',
        'description',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];
}
