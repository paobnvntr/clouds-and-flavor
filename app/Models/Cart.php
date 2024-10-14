<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'quantity',
        'addon_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'cart_add_on')->withPivot('price');
    }

    public function OrderaddOns()
    {
        return $this->hasMany(OrderAddOn::class, 'cart_id'); 
    }
}
