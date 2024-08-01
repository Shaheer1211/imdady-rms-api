<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MultiplePayment extends Model
{
    use HasFactory;
    protected $table = 'multiple_payments';

    protected $fillable = [
        'payment_method_id',
        'company_name',
        'dis_type',
        'expired_date',
        'status',
        'discount_amount',
        'photo',
        'del_status',
    ];

    public function delete()
    {
        $this->del_status = 'delete';
        $this->save();
    }

    public function restore()
    {
        $this->del_status = 'Live';
        $this->save();
    }
    public function getDeletedAtColumn()
    {
        return 'del_status'; // Use 'del_status' for soft delete check
    }

    public function getDeletedAt()
    {
        return null;
    }

    public function paymentMethod()
    {
        return $this->belongsTo(Payments::class, 'payment_method_id', 'id');
    }
}
