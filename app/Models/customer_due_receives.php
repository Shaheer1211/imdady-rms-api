<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class customer_due_receives extends Model
{
    use HasFactory;
    protected $fillable = [
        'reference_no',
        'only_date',
        'amount',
        'customer_id',
        'note',
        'user_id',
        'outlet_id',
        'del_status'
    ];
}
