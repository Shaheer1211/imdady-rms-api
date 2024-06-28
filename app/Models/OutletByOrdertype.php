<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletByOrdertype extends Model
{
    protected $table = 'outlet_by_ordertype';
    public $timestamps = false;
    use HasFactory;
    protected $fillable = [
        'id',
        'ordertype_id',
        'outlet_id',
    ];
}
