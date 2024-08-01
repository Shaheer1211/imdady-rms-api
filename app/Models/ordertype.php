<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordertype extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'type',
        'name',
        'status',
        'created_at',
        'updated_at'
    ];
    public function outlets()
    {
        return $this->belongsToMany(Outlet::class, 'outlet_by_ordertype', 'ordertype_id', 'outlet_id');
    }
}
