<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class waste extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'reference_no',
        'date',
        'total_loss',
        'note',
        'food_menu_id',
        'food_menu_waste_qty',
        'user_id ',
        'outlet_id',
        'del_status'
    ];
}
