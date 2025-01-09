<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Tampilkan halaman utama pelanggan.
     */
    public function home()
    {
        return view('customer.cart');
    }

    public function addToCart(Request $request)
{
    $cart = session()->get('cart', []);

    // Tambahkan item ke keranjang
    $cart[] = $request->only('id', 'name', 'price');

    // Simpan kembali ke sesi
    session(['cart' => $cart]);

    return response()->json(['success' => true]);
}

public function checkout(Request $request)
{
    $cartData = $request->input('cart');
    // Proses data keranjang, misalnya simpan ke database atau buat transaksi
    return response()->json(['success' => true, 'message' => 'Checkout successful']);
}



}
