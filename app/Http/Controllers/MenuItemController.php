<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    /**
     * Tampilkan semua menu item untuk pelanggan
     */
    public function index()
    {
        // Ambil semua kategori beserta menu yang terkait
        $categories = Category::with('menuItems')->get();
        return view('admin.menu-items.index', compact('categories'));
    }


    /**
     * Tampilkan semua menu item untuk admin
     */
    public function adminIndex()
    {
        $menuItems = MenuItem::with('category')->get();
        return view('admin.menu-items.index', compact('menuItems')); // Untuk panel admin
    }

    /**
     * Halaman buat menu item baru
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.menu-items.create', compact('categories'));
    }

    /**
     * Simpan menu item baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'required|image',
            'description' => 'nullable|string',
        ]);

        // Simpan gambar ke storage
        $imagePath = $request->file('image')->store('menu-images', 'public');

        // Simpan data menu item
        MenuItem::create(array_merge(
            $request->all(),
            ['image' => $imagePath]
        ));

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item created successfully.');
    }

    /**
     * Halaman edit menu item
     */
    public function edit(MenuItem $menuItem)
    {
        $categories = Category::all();
        return view('admin.menu-items.edit', compact('menuItem', 'categories'));
    }

    /**
     * Update menu item
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'image' => 'nullable|image',
            'description' => 'nullable|string',
        ]);

        // Jika ada file gambar baru, hapus yang lama dan simpan yang baru
        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($menuItem->image);
            $menuItem->image = $request->file('image')->store('menu-images', 'public');
        }

        $menuItem->update($request->all());

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item updated successfully.');
    }

    /**
     * Hapus menu item
     */
    public function destroy(MenuItem $menuItem)
    {
        // Hapus gambar dari storage
        Storage::disk('public')->delete($menuItem->image);

        $menuItem->delete();

        return redirect()->route('admin.menu-items.index')->with('success', 'Menu item deleted successfully.');
    }
}
