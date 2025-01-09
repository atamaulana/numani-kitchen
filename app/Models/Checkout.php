<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Checkout extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'phone_number',
        'table_number',
        'floor_number',
        'payment_method',
        'status',
        'subtotal',
    ];

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    protected static function booted()
    {
        static::creating(function ($checkout) {
            $checkout->order_code = 'ORD-' . date('Ymd') . '-' . Str::upper(Str::random(6));
        });
    }

    public function items()
    {
        return $this->hasMany(CheckoutItem::class);
    }

    public function checkoutItems()
    {
        return $this->hasMany(CheckoutItem::class, 'checkout_id');
    }
}
