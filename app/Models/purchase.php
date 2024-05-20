<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        'reference_no',
        'supplier_id',
        'date',
        'subtotal',
        "vat	",
        'grand_total	',
        'paid',
        'due',
        "note",
        'user_id',
        'outlet_id	', 
        'created_at',
        'del_status',
    ];
}
