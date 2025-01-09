<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Pesanan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 20px;
            width: 80%;
            margin: 0 auto;
            border: 1px solid #ddd;
            border-radius: 10px;
        }
        .header {
            text-align: center;
        }
        .header h2 {
            margin: 0;
        }
        .details {
            margin: 20px 0;
        }
        .details p {
            margin: 5px 0;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        .footer {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Numani Kitchen & Coffee</h2>
            <p>Alamat: Jl. [Nama Jalan], Bandung</p>
            <p>Telepon: [Nomor Telepon]</p>
        </div>
        <div class="details">
            <p><strong>Tanggal:</strong> {{ $order->created_at->format('d-m-Y') }}</p>
            <p><strong>Waktu:</strong> {{ $order->created_at->format('H:i') }}</p>
            <p><strong>Nomor Transaksi:</strong> {{ $order->id }}</p>
            <p><strong>Nomor Meja:</strong> {{ $order->table_number }}</p>
        </div>
        <h3>Rincian Pesanan:</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Item</th>
                    <th>Kategori</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->orderItems as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->menuItem->name }}</td>
                    <td>{{ $item->menuItem->category->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->menuItem->price, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->quantity * $item->menuItem->price, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="footer">
            <p><strong>Total Pesanan:</strong> Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
            <p><strong>Metode Pembayaran:</strong> {{ $order->payment_method }}</p>
            <p><strong>Status Pembayaran:</strong> {{ $order->status }}</p>
        </div>
    </div>
</body>
</html>
