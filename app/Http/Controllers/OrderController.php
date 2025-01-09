<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{

    public function cart()
    {
        // Logika untuk menampilkan halaman keranjang
        return view('customer.cart'); // Pastikan view ini ada
    }

    // Method untuk mencetak struk pesanan
    public function print(Order $order)
    {
        // Memuat relasi untuk order, misalnya item-menu dan kategori
        $order->load('orderItems.menuItem.category');

        // Membuat PDF dari view
        $pdf = Pdf::loadView('admin.order.receipt', compact('order'));

        // Mendownload PDF
        return $pdf->download('Struk-Pesanan-' . $order->id . '.pdf');
    }

    // Jika Anda membutuhkan method index untuk menampilkan daftar order
    public function index()
    {
        // Ambil semua data pesanan
        $orders = Order::all();

        // Kirimkan data pesanan ke view
        return view('admin.order.index', compact('orders'));
    }
}

