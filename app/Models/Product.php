<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'status',
        'stock',
        'category_id',
        'description',
        'price',
        'image',
        'sale_price',
        'on_sale'

    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
