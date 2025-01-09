<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'transaksi';

    // Kolom-kolom yang dapat diisi secara massal
    protected $fillable = [
        'tanggal',
        'menu',
        'nama_pelanggan',
        'no_meja',
        'cara_pembayaran',
    ];
}
