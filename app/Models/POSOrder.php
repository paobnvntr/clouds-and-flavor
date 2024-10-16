<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSOrder extends Model
{
    use HasFactory;

    protected $table = 'pos_order'; 

    protected $fillable = [
        'staff_id',
        'customer_name',
        'total_price',
        'status',
        'table_number',
        'amount',
        'payment_method',
    ];

    public function staff()
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    public function orderItems()
    {
        return $this->hasMany(POSOrderItem::class, 'pos_order_id');
    }

    public function getCustomerName()
    {
        return $this->customer_name;
    }   
}
