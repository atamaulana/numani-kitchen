<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySlugSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi tabel kategori.
     */
    public function run()
    {
        $categories = [
            ['name' => 'Menu Baru', 'slug' => Str::slug('Menu Baru')],
            ['name' => 'Menu Paket', 'slug' => Str::slug('Menu Paket')],
            ['name' => 'Menu Satuan', 'slug' => Str::slug('Menu Satuan')],
            ['name' => 'Menu Minuman', 'slug' => Str::slug('Menu Minuman')],
            ['name' => 'Menu Snacks', 'slug' => Str::slug('Menu Snacks')],
            ['name' => 'Coffee Premium', 'slug' => Str::slug('Coffee Premium')],
        ];

        foreach ($categories as $category) {
            Category::updateOrCreate(['name' => $category['name']], $category);
        }
    }
}
