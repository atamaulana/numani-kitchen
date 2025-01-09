<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        // Ambil semua kategori dan menu dengan relasi
        $categories = Category::with('menuItems')->get();

        return view('welcome', compact('categories'));
    }

}
