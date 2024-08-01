<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'customer_id',
        'is_coupon',
        'coupon_id',
        'coupon_discount_amount',
        'cashier_id',
        'sale_no',
        'token_no',
        'total_items',
        'sub_total',
        'paid_amount',
        'discount',
        'vat_amount',
        'qrcode',
        'total_payable',
        'loyalty_point_amount',
        'close_time',
        'table_id',
        'total_item_discount_amount',
        'sub_total_with_discount',
        'delivery_charges',
        'sale_date',
        'date_time',
        'order_time',
        'cooking_start_time',
        'cooking_end_time',
        'modified',
        'modified_vat',
        'user_id',
        'waiter_id',
        'outlet_id',
        'order_status',
        'order_type_id',
        'order_from',
        'main_screen_discount_type',
        'main_screen_discount',
        'card_discount_type',
        'card_discount',
        'payment_method_id',
        'multiple_payments',
        'order_menu_taxes',
        'created_at',
        'updated_at',
        'del_status',
    ];

    public function orderDetails()
    {
        return $this->hasMany(OrderDetails::class, 'order_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function payment()
    {
        return $this->belongsTo(Payments::class, 'payment_method_id');
    }

    public $timestamps = true;

    protected $casts = [
        'multiple_payments' => 'array',
        'order_menu_taxes' => 'array',
    ];
}
