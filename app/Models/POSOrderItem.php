<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POSOrderItem extends Model
{
    use HasFactory;

    protected $table = 'pos_order_items'; 

    protected $fillable = [
        'pos_order_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function order()
    {
        return $this->belongsTo(POSOrder::class, 'pos_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
