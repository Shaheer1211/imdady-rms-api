<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'value',
        'status',
        'user_id',
        'outlet_id',
    ];
}
