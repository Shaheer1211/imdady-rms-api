<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ordertype extends Model
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
}
