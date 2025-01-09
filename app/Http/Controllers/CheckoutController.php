<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Cart;
use App\Models\Checkout;
use App\Models\CheckoutItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function index()
    {
        $carts = Cart::where('session_id', session()->getId())->whereNull('checkout_id')->get();
        return view('checkout.index', compact('carts'));
    }

    public function store(Request $request)
    {
        // Validasi input form
        $validated = $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:15',
            'table_number' => 'required|string|max:10',
            'floor_number' => 'required|string|max:10',
            'payment_method' => 'required|in:cash,qris',
            'subtotal' => 'required|numeric|min:0.01',  // Validasi subtotal
        ]);

        // Membuat data checkout
        $checkout = Checkout::create($validated);

        // Ambil data keranjang berdasarkan session
        $carts = Cart::where('session_id', session()->getId())->get();
        $subtotal = 0;

        // Menghitung subtotal dan menyimpan items ke checkout_item
        foreach ($carts as $cart) {
            $totalPrice = $cart->menuItem->price * $cart->quantity;

            CheckoutItem::create([
                'checkout_id' => $checkout->id,
                'menu_name' => $cart->menuItem->name,
                'menu_image' => $cart->menuItem->image,
                'menu_price' => $cart->menuItem->price,
                'quantity' => $cart->quantity,
                'total_price' => $totalPrice,
                'level_pedas' => $cart->level_pedas ?? null,   // Perbaikan di sini
                'panas_dingin' => $cart->panas_dingin ?? null, // Perbaikan di sini
                'level_es' => $cart->level_es ?? null,         // Perbaikan di sini
                'manis' => $cart->manis ?? null,               // Perbaikan di sini
                'notes' => $cart->notes ?? null,               // Perbaikan di sini
            ]);

            // Tambahkan total harga item ke subtotal
            $subtotal += $totalPrice;

            // Hapus item dari keranjang setelah checkout
            $cart->delete();
        }

        // Menyimpan subtotal ke tabel checkout
        $checkout->subtotal = $subtotal;
        $checkout->save();

        // Jika metode pembayaran adalah 'qris', lakukan proses Midtrans
        if ($request->payment_method === 'qris') {
            return $this->getSnapToken($checkout);
        }

        // Jika metode pembayaran adalah 'cash', arahkan ke halaman success
        return redirect()->route('checkout.success', ['id' => $checkout->id]);
    }

    // Fungsi untuk mendapatkan Snap Token dari Midtrans
    public function getSnapToken(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_name' => 'required|string|max:255',
                'subtotal' => 'required|numeric|min:0.01',  // Validasi subtotal
            ]);

            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            // Parameter transaksi untuk Midtrans
            $params = [
                'transaction_details' => [
                    'order_id' => 'ORDER-' . time(),
                    'gross_amount' => $validated['subtotal'],  // Gunakan subtotal yang sudah dihitung
                ],
                'customer_details' => [
                    'first_name' => $validated['customer_name'],
                    'phone' => $request->phone_number,
                ],
            ];

            // Ambil Snap Token dari Midtrans
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return response()->json(['snap_token' => $snapToken]);
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            return response()->json(['error' => 'Error generating snap token.'], 500);
        }
    }

    // Halaman sukses setelah transaksi selesai
    public function success($id)
    {
        $checkout = Checkout::with('checkoutItems')->findOrFail($id);
        return view('checkout.success', compact('checkout'));
    }

    // Menghapus transaksi
    public function destroy($id)
    {
        $checkout = Checkout::find($id);

        if ($checkout) {
            $checkout->delete();
            return response()->json(['message' => 'Transaksi berhasil dihapus.'], 200);
        }

        return response()->json(['message' => 'Transaksi tidak ditemukan.'], 404);
    }
}
