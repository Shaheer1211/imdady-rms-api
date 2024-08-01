<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Outlet extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'email',
        'address',
        'city_id',
        'status',
        'registration_no',
        'user_id'
    ];

    protected $dates = ['deleted_at'];
    public function ordertypes()
    {
        return $this->belongsToMany(Ordertype::class, 'outlet_by_ordertype', 'outlet_id', 'ordertype_id');
    }
}
