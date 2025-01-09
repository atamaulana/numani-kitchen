<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    use HasFactory;

    protected $table = 'menu-items'; // Nama tabel yang benar
    protected $fillable = ['name', 'category_id', 'price', 'stock', 'image', 'description'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');

    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'menu_items_id', 'id'); // Pastikan nama kolom juga sesuai
    }
}
