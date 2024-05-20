<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class topbanner extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'text',
        'status',
        'created_at',
        'updated_at'
    ];
}
