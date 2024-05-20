<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class companies extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'currency',
        'timezone',
        'date_format',
        'outlet_id',
    ];

    protected $dates = ['deleted_at'];
}
