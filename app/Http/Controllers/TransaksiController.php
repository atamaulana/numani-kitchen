<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Checkout;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    public function index()
    {
        $checkouts = Checkout::orderBy('created_at', 'desc')->get(); // Ambil semua data checkout

        return view('admin.transaksi.index', compact('checkouts')); // Kirim data ke view

    }

    public function getTransactions(Request $request)
    {
        $startDate = $request->start_date;
        $endDate = $request->end_date;

        // Validasi input tanggal
        if (!$startDate || !$endDate) {
            return response()->json([], 400);
        }

        // Query data transaksi berdasarkan rentang tanggal
        $transactions = Transaksi::whereBetween('created_at', [$startDate, $endDate])->get();

        // Return data sebagai JSON
        return response()->json($transactions);
    }

    public function payTransaction(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:checkouts,id',
            'amount_paid' => 'required|numeric|min:0',
        ]);

        $checkout = Checkout::findOrFail($request->id);

        // Validasi uang cukup
        if ($request->amount_paid < $checkout->subtotal) {
            return redirect()->back()->with('error', 'Uang pelanggan tidak cukup!');
        }

        // Perbarui status dan simpan pembayaran
        $checkout->status = 'paid';
        $checkout->save();

        return redirect()->route('admin.transaksi.index')->with('success', 'Transaksi berhasil dibayar!');
    }

    public function updateStatus(Request $request, $id)
    {
        // Logika untuk memperbarui status transaksi
        $transaction = Transaksi::findOrFail($id);
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('admin.transaksi.index')->with('success', 'Status transaksi diperbarui!');
    }


}
