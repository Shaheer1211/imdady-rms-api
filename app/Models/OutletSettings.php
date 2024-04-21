<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletSettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'outlet_name',
        'service_delivery_charge',
        'outlet_code',
        'address',
        'phone',
        'whatsapp_number',
        'email',
        'days',
        'hours',
        'invoice_print',
        'is_android',
        'starting_date',
        'printer_local_ip',
        'invoice_footer',
        'invoice_footer_text',
        'invoice_footer_text_1',
        'order_date',
        'statement_date',
        'print_date',
        'printer_detail',
        'jspm_print',
        'print_kot_invoice',
        'collect_tax',
        'tax_title',
        'tax_percentage',
        'invoice',
        'theme_design',
        'invoice_language',
        'round_off',
        'logo_name',
        'tax_registration_no',
        'tax_is_gst',
        'sale_date_time',
        'state_code',
        'pre_or_post_payment',
        'user_id',
        'outlet_id',
        'created_at',
        'updated_at',
        'del_status'
    ];
}
