<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class credit extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'name',
        'discount',
        'discount type',
        'expiry date',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
