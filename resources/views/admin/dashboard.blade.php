@extends('admin.layout')

@section('title', 'Dashboard')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="navbar fixed-top" style="background-color: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #ddd; z-index: 1050;">
    <h2 style="margin: 0;">Panel <span style="color: #d0aa11">Admin</span> - <span>Dashboard</span></h2>
</div>
<!-- Row untuk card -->
<div class="row" style="margin-top: 100px">
    <!-- Card untuk Total Pengunjung -->
    <div class="col-md-2 mb-4">
        <div class="card text-white" style="background-color: #D0AA11">
            <div class="card-body">
                <h5 class="card-title">Total Pengunjung</h5>
                <p class="card-text">{{ $totalVisitors }} Pengunjung</p> <!-- Menampilkan jumlah pengunjung -->
            </div>
        </div>
    </div>

    <!-- Card untuk Total Penjualan -->
    <div class="col-md-2 mb-4">
        <div class="card text-white" style="background-color: #183D3D">
            <div class="card-body">
                <h5 class="card-title">Total Penjualan</h5>
                <p class="card-text">Rp {{ number_format($totalPenjualan, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Card untuk Total Transaksi -->
    <div class="col-md-2 mb-4">
        <div class="card text-white" style="background-color: #470031">
            <div class="card-body">
                <h5 class="card-title">Transaksi (Terbayar)</h5>
                <p class="card-text">{{ $totalTransaksi }} Transaksi</p>
            </div>
        </div>
    </div>

    <!-- Card untuk Total Transaksi -->
    <div class="col-md-2 mb-4">
        <div class="card text-white" style="background-color: #16213E">
            <div class="card-body">
                <h5 class="card-title">Transaksi (Belum Dibayar)</h5>
                <p class="card-text">{{ $totalUnpaid }} Transaksi</p>
            </div>
        </div>
    </div>

    <!-- Card untuk Rata-Rata Penjualan -->
    <div class="col-md-2 mb-4">
        <div class="card text-white" style="background-color: #561C24">
            <div class="card-body">
                <h5 class="card-title">Rata-Rata Penjualan</h5>
                <p class="card-text">Rp {{ number_format($rataRataPenjualan, 2, ',', '.') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <!-- Bar Chart -->
        <div class="col-md-6">
            <div class="container-bar">
                <h1>Diagram Batang Penjualan</h1>
                <canvas id="barChart" class="barChart"></canvas>
            </div>
        </div>

        <!-- Recent Orders Table -->
        <div class="col-md-6">
            <div class="content-table">
                <h1>Pesanan Terbaru</h1>
                <table class="table-dashboard table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Customer Name</th>
                            <th>Order Code</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($checkouts->take(5) as $key => $checkout)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $checkout->customer_name }}</td>
                                <td>{{ $checkout->order_code }}</td>
                                <td>
                                    <span class="badge {{ $checkout->status === 'paid' ? 'bg-success' : 'bg-danger' }}">
                                        {{-- {{ ucfirst($checkout->status) }} --}} {{ $checkout->status === 'paid' ? 'Terbayar' : 'Belum Terbayar' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No data available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


<script>
    // Data labels bulan (Januari, Februari, dll.)
    const bulanLabels = [
        'Jan', 'Feb', 'Maret', 'April',
        'Mei', 'Juni', 'Juli', 'Agustus',
        'Sept', 'Okt', 'Nov', 'Des'
    ];

    // Data warna yang berbeda untuk setiap bulan
    const warnaBar = [
        'rgba(255, 99, 132, 0.6)', // Warna merah muda
        'rgba(54, 162, 235, 0.6)', // Warna biru
        'rgba(255, 206, 86, 0.6)', // Warna kuning
        'rgba(75, 192, 192, 0.6)', // Warna hijau
        'rgba(153, 102, 255, 0.6)', // Warna ungu
        'rgba(255, 159, 64, 0.6)', // Warna jingga
        'rgba(99, 255, 132, 0.6)', // Warna hijau muda
        'rgba(162, 54, 235, 0.6)', // Warna ungu gelap
        'rgba(206, 255, 86, 0.6)', // Warna kuning pucat
        'rgba(192, 75, 192, 0.6)', // Warna magenta
        'rgba(102, 153, 255, 0.6)', // Warna biru muda
        'rgba(159, 255, 64, 0.6)'  // Warna hijau lemon
    ];

    const dataPenjualan = @json($penjualanBulanan->pluck('total_penjualan')); // Data penjualan

    const ctx = document.getElementById('barChart').getContext('2d');
    const barChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: bulanLabels, // Menggunakan label bulan lengkap
            datasets: [{
                label: 'Penjualan',
                data: dataPenjualan, // Data penjualan
                backgroundColor: warnaBar, // Warna batang
                borderColor: warnaBar.map(color => color.replace('0.6', '1.0')), // Warna border
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                x: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Jumlah Penjualan'
                    },
                    ticks: {
                        stepSize: 1000000, // Langkah per 5 juta
                        callback: function(value) {
                            return value.toLocaleString('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 });
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true
                }
            }
        }
    });
</script>

@endsection
