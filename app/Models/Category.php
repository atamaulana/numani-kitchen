<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    // Event saat kategori dibuat atau diperbarui
    protected static function booted()
    {
        static::saving(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name); // Buat slug otomatis
            }
        });
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class);
    }
}
