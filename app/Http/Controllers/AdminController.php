<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use App\Models\Checkout;
use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function dashboard()
    {

        // Menghitung jumlah pengunjung yang unik berdasarkan customer_name
        $totalVisitors = Checkout::distinct('customer_name')->count('customer_name');

        // Mengambil data dari tabel checkouts
        $checkouts = Checkout::select('customer_name', 'order_code', 'status')
            ->orderBy('created_at', 'desc')  // Mengurutkan berdasarkan created_at terbaru
            ->get();

        // Mengambil total penjualan (total subtotal dari semua transaksi yang sudah dibayar)
        $totalPenjualan = Checkout::where('status', 'paid')->sum('subtotal');

        // Mengambil jumlah transaksi yang sudah dibayar
        $totalTransaksi = Checkout::where('status', 'paid')->count();

        // Mengambil jumlah transaksi yang belu, dibayar
        $totalUnpaid = Checkout::where('status', 'unpaid')->count();

        // Mengambil rata-rata penjualan per transaksi
        $rataRataPenjualan = $totalTransaksi ? $totalPenjualan / $totalTransaksi : 0;


        // Mengambil total penjualan per bulan selama 6 bulan terakhir
        $penjualanBulanan = Checkout::where('status', 'paid')
        ->selectRaw('SUM(subtotal) as total_penjualan, MONTH(created_at) as bulan')
        ->groupBy('bulan')
        ->orderBy('bulan')
        ->take(6)
        ->get();

        // Mengirim data ke view dashboard
        return view('admin.dashboard', compact('checkouts', 'totalVisitors', 'totalPenjualan', 'totalTransaksi', 'rataRataPenjualan', 'penjualanBulanan', 'totalUnpaid'));
    }
}
