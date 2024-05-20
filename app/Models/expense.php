<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expense extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'date',
        'amount',
        'vat',
        'total_amount',
        'vat_percentage',
        'tax_method',
        'reference',
        'category_id ',
        'payment_method_id',
        'employee_id',
        'note',
        'user_id',
        'outlet_id',
        'del_status',
        'created_at',
        
        
    ];

    protected $dates = ['deleted_at'];
}
