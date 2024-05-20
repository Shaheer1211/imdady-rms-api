<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suppliers extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        'name',
        'contact_person',
        'phone',
        'supplier_vat',
        "email",
        'address',
        'description',
        'user_id',
        'outlet_id',
        "del_status",
        
       
    ];

}
