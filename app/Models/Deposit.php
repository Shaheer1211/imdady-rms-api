<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'name',
        'phone',
        'amount',
        'note',
        'return_amount',
        'date',
        'description',
        'status',
        'user_id',
        'company_id',
    ];

    protected $dates = ['deleted_at'];
}
