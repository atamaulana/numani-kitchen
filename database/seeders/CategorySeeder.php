<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Menambahkan kategori dengan nama dan slug tanpa deskripsi
        Category::create([
            'name' => 'Menu Baru',
            'slug' => Str::slug('Menu Baru'),
        ]);

        Category::create([
            'name' => 'Menu Paket',
            'slug' => Str::slug('Menu Paket'),
        ]);

        Category::create([
            'name' => 'Menu Satuan',
            'slug' => Str::slug('Menu Satuan'),
        ]);

        Category::create([
            'name' => 'Menu Minuman',
            'slug' => Str::slug('Menu Minuman'),
        ]);

        Category::create([
            'name' => 'Menu Snacks',
            'slug' => Str::slug('Menu Snacks'),
        ]);

        Category::create([
            'name' => 'Coffee Premium',
            'slug' => Str::slug('Coffee Premium'),
        ]);
    }
}

