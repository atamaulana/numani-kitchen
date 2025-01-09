<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['session_id', 'menu_items_id', 'quantity', 'level_pedas', 'panas_dingin', 'level_es', 'manis', 'notes',];

    /**
     * Relasi ke tabel menu_items
     */
    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class, 'menu_items_id');
    }

    public function checkout()
    {
        return $this->belongsTo(Checkout::class);
    }
}
