<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAddOn extends Model
{
    use HasFactory;

    protected $table = 'orders_add_on';

    protected $fillable = ['order_id', 'add_on_id', 'price', 'quantity'];

    // Relationship with Order model
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relationship with Product model
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function addOn()
    {
        return $this->belongsTo(AddOn::class, 'add_on_id');
    }
}
