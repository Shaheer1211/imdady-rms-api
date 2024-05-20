<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class expenses_category extends Model
{
    use HasFactory;
    protected $fillable = [
        'id ',
        'name',
        'vat_percentage',
        'tax_method',
        'description',
        'user_id',
        'company_id',
        'del_status',
        
        
    ];

    protected $dates = ['deleted_at'];
}
