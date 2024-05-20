<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inventoryadjustment extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'reference_no',
        'date',
        'note',
        'employee_id',
        'user_id',
        'outlet_id',
        'del_status',
        
    ];
}
