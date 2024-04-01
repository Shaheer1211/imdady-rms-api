<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modifiers extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'name',
        'price',
        'description',
        'tax_method',
        'tax',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];
}
