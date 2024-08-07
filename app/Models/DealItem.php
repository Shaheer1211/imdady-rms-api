<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealItem extends Model
{
    use HasFactory;
    
    protected $table = 'deal_item';

    protected $fillable = [
        'id',
        'deal_id',
        'item_id',
        'quantity'
    ];

    public function deal()
    {
        return $this->belongsTo(Deal::class, 'deal_id');
    }

    public function foodMenu()
    {
        return $this->belongsTo(FoodMenus::class, 'item_id');
    }
}
