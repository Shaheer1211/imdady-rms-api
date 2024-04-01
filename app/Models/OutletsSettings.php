<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OutletsSettings extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'outlet_id',
        'service_delivery_charge',
        'whatsapp_number',
        'days',
        'hours',
        'is_invoice_print',
        'is_android',
        'printer_local_ip',
        'is_print_type',
        'is_jspm_print',
        'template',
        'is_round_off',
    ];

    protected $dates = ['deleted_at'];
}
