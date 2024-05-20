<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class supplierpayment extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        'date',
        'supplier_id ',
        'amount',
        'note',
        "user_id",
        'outlet_id',
        'del_status',
    ];

}
