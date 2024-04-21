<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class returns extends Model
{
    use HasFactory;
    protected $fillable = [
        "id",
        'ref_no',
        'invoice_no	',
        'date_time',
        'description',
        'user_id',
        'return_amount',
        'return_vat',
        'total_return_amount',
        'qrcode',
        'company_id',
        'del_status',
    ];
}
