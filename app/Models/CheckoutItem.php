<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checkout_id',
        'menu_name',
        'menu_image',
        'menu_price',
        'quantity',
        'total_price',
        'level_pedas',
        'panas_dingin',
        'level_es',
        'manis',
        'notes',
    ];

    public function checkout()
    {
        return $this->belongsTo(Checkout::class, 'checkout_id');
    }

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_items_id');
    }
}

