<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class attendance extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'reference_no',
        'employee_id',
        'date',
        'in_time',
        'out_time ',
        'note',
        'user_id',
        'outlet_id',
        'del_status ',
    ];

    protected $dates = ['deleted_at'];
}
