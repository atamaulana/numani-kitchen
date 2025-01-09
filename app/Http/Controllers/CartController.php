<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\MenuItem;

class CartController extends Controller
{
    public function index()
    {
        $menuItems = MenuItem::all();
        return view('welcome', compact('menuItems'));
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'menu_items_id' => 'required|exists:menu-items,id',
            'quantity' => 'required|integer|min:1',
            'level_pedas' => 'nullable|string',
            'panas_dingin' => 'nullable|string',
            'level_es' => 'nullable|string',
            'manis' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $sessionId = $request->session()->getId();

        // Periksa apakah item sudah ada dalam keranjang
        $cart = Cart::where('session_id', $sessionId)
            ->where('menu_items_id', $request->menu_items_id)
            ->first();

        if ($cart) {
            // Update kustomisasi jika ada perubahan
            $cart->update([
                'quantity' => $request->quantity,
                'level_pedas' => $request->level_pedas,
                'panas_dingin' => $request->panas_dingin,
                'level_es' => $request->level_es,
                'manis' => $request->manis,
                'notes' => $request->notes,
            ]);
        } else {
            // Jika item belum ada, buat keranjang baru dengan kustomisasi
            Cart::create([
                'session_id' => $sessionId,
                'menu_items_id' => $request->menu_items_id,
                'quantity' => $request->quantity,
                'level_pedas' => $request->level_pedas,
                'panas_dingin' => $request->panas_dingin,
                'level_es' => $request->level_es,
                'manis' => $request->manis,
                'notes' => $request->notes,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Item berhasil ditambahkan ke keranjang']);
    }

    public function cartCount(Request $request)
    {
        $sessionId = $request->session()->getId();
        $count = Cart::where('session_id', $sessionId)->sum('quantity');

        return response()->json(['count' => $count]);
    }

    public function getCartItems(Request $request)
    {
        $sessionId = $request->session()->getId();
        $carts = Cart::where('session_id', $sessionId)->with('menuItem')->get();

        return response()->json(['carts' => $carts]);
    }

    public function edit($id)
    {
        $cart = Cart::with('menuItem')->find($id);

        if (!$cart) {
            return response()->json(['message' => 'Keranjang tidak ditemukan'], 404);
        }

        return response()->json(['cart' => $cart]);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);

        if (!$cart) {
            return response()->json(['message' => 'Keranjang tidak ditemukan'], 404);
        }

        // Log untuk debugging
        Log::info('Data sebelum update:', $cart->toArray());
        Log::info('Data yang diterima:', $request->all());

        // Validasi input quantity
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        // Perbarui nilai
        $cart->update([
            'quantity' => $validated['quantity'], // Replace dengan nilai baru
            'level_pedas' => $request->level_pedas,
            'panas_dingin' => $request->panas_dingin,
            'level_es' => $request->level_es,
            'manis' => $request->manis,
            'notes' => $request->notes,
        ]);

        // Debug: log setelah update
        Log::info('Data setelah update:', $cart->toArray());

        return response()->json(['message' => 'Keranjang berhasil diperbarui']);
    }

    public function delete($id)
    {
        $cart = Cart::findOrFail($id);
        $cart->delete();

        return response()->json(['success' => true, 'message' => 'Item berhasil dihapus']);
    }
}

