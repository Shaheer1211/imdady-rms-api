<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payments extends Model
{
    use HasFactory;

    protected $table = 'payment_methods';
    protected $fillable = [
        'name',
        'description',
        'status',
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

    public function multiplePayments()
    {
        return $this->hasMany(MultiplePayment::class, 'payment_method_id', 'id');
    }

}
