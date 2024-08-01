<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sit_capacity',
        'position',
        'description',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
