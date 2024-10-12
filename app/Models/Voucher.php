<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'discount',
        'type',
        'expiry_date',
        'is_active',
        'usage_limit',
        'times_used',
        'minimum_purchase',
        'max_discount'
    ];

    // Scope to check if a voucher is still valid
    public function scopeValid($query)
    {
        return $query->where('expiry_date', '>=', now())
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhere('times_used', '<', DB::raw('usage_limit'));
            });
    }
}
