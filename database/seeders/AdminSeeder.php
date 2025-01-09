<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminSeeder extends Seeder
{
    public function run()
    {
        Admin::updateOrCreate(
            ['email' => 'admin@coffeeshop.com'],
            [
                'name' => 'Super Admin',
                'password' => bcrypt('admin123') // Simpan password yang di-hash
            ]
        );
    }
}
