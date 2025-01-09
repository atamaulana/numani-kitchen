<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <!-- Tambahkan CSS di sini -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container" style="background-color: white; max-width: 400px; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
            <div class="modal fade" id="detailModal{{ $checkout->id }}" aria-labelledby="detailModalLabel{{ $checkout->id }}" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body">
                            <p style="text-align: center; font-size: 20px;"> Harap Screenshot Halaman ini sebagai Bukti Pembayaran ke Kasir</p>
                            <div class="receipt">
                                <div class="receipt-header">
                                    <h3 class="text-center" style="text-align: center">Numani Coffee & Kitchen</h3>
                                    <p class="text-center" style="text-align: center">Jl. Cibanteng No.36 2, RT.2/RW.1, Koja, Jkt Utara, DKI Jakarta</p>
                                    <p class="text-center" style="text-align: center">Telp: 0857-3118-0094</p>
                                    <hr>
                                </div>

                                <div class="receipt-body">
                                    <p><strong>Tangggal:</strong> {{ $checkout->created_at }}</p>
                                    <p><strong>Kode:</strong> {{ $checkout->order_code }}</p>
                                    <p><strong>Nama:</strong> {{ $checkout->customer_name }}</p>
                                    <p><strong>Nomor Meja:</strong> {{ $checkout->table_number }}</p>
                                    <p><strong>Nomor Lantai:</strong> {{ $checkout->floor_number }}</p>
                                    <p><strong>Nomor Telepon:</strong> {{ $checkout->phone_number }}</p>
                                    <p><strong>Metode Pembayaran:</strong> {{ ucfirst($checkout->payment_method) }}</p>
                                    <p><strong>Status Pembayaran:</strong> {{ $checkout->status }}</p>
                                    <hr>
                                </div>

                                <div class="receipt-items">
                                    <h3 style="text-align: center">Pesanan Anda:</h3>
                                    <div class="order-body">
                                        <table style="border-collapse: collapse; width: 100%; border: 1px solid #ddd;">
                                            <thead>
                                                <tr style="background-color: #ddd;">
                                                    <th style="padding: 8px; border: 1px solid #ddd;">Menu</th>
                                                    <th style="padding: 8px; border: 1px solid #ddd;">Harga</th>
                                                    <th style="padding: 8px; border: 1px solid #ddd;">Total</th>
                                                    <th style="padding: 8px; border: 1px solid #ddd;">Kustomisasi</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($checkout->items as $item)
                                                <tr>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $item->quantity }} {{ $item->menu_name }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($item->menu_price, 0, ',', '.') }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                                                    <td style="padding: 8px; border: 1px solid #ddd;">
                                                        <!-- Menampilkan kustomisasi item -->
                                                        @if($item->level_pedas || $item->panas_dingin || $item->level_es || $item->manis || $item->notes)
                                                        <br>
                                                        <small>
                                                            @if($item->level_pedas) Level Pedas: {{ $item->level_pedas }}@endif
                                                            @if($item->level_pedas && $item->panas_dingin) | @endif
                                                            @if($item->panas_dingin) Suhu: {{ $item->panas_dingin }}@endif
                                                            @if($item->panas_dingin && $item->level_es) | @endif
                                                            @if($item->level_es) Level Es: {{ $item->level_es }}@endif
                                                            @if($item->level_es && $item->manis) | @endif
                                                            @if($item->manis) Manis: {{ $item->manis }}@endif
                                                            @if($item->notes) | Catatan: {{ $item->notes }}@endif
                                                        </small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <hr>
                                </div>
                                <div class="receipt-footer">
                                    <p><strong>Subtotal:</strong> Rp {{ number_format($checkout->subtotal, 0, ',', '.') }}</p>
                                    <hr>
                                    <p class="text-center" style="text-align: center">Terima Kasih atas kunjungan Anda!</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <button type="button" class="btn" onclick="window.location.href='/'" style="margin: 10px">Kembali ke Beranda</button>
    </div>
</body>
</html>

