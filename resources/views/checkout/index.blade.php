<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout</title>
    <!-- Tambahkan CSS di sini -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Tambahkan di bagian <head> -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</head>
<body>
    <h1 style="text-align: center; color: #D0AA11; font-weight: 700;">ISI DATA PESANAN</h1>
    @if($carts->isEmpty())
        <p>Keranjang Anda kosong. Silakan pilih menu terlebih dahulu.</p>
    @else
        <form action="{{ route('checkout.store') }}" method="POST" onsubmit="console.log('Form submitted!')">
            @csrf
            <!-- Menyembunyikan ID checkout di elemen input -->
            <input type="hidden" id="checkout_id" value="{{ $checkout->id ?? '' }}">
            <!-- Field subtotal tersembunyi -->
            <input type="hidden" id="subtotal" name="subtotal" value="{{ $carts->sum(function($cart) { return $cart->menuItem->price * $cart->quantity; }) }}">
            <!-- Menampilkan Error -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div>
                <label for="customer_name">Nama Pelanggan:</label>
                <input type="text" id="customer_name" name="customer_name" required>
            </div>
            <div>
                <label for="phone_number">Nomor Telepon:</label>
                <input type="text" id="phone_number" name="phone_number" required>
            </div>
            <div>
                <label for="table_number">Nomor Meja:</label>
                <input type="number" id="table_number" name="table_number" required>
            </div>
            <div>
                <label for="floor_number">Nomor Lantai:</label>
                <select id="floor_number" name="floor_number" required>
                    <option value="" disabled selected>Pilih lantai</option>
                    <option value="1">Lantai 1</option>
                    <option value="2">Lantai 2</option>
                </select>
            </div>
            <div>
                <label for="payment_method">Metode Pembayaran:</label>
                <select id="payment_method" name="payment_method" required>
                    <option value="cash">Tunai</option>
                    <option value="qris">QRIS</option>
                </select>
            </div>

            <div class="order-list">
                <h5>Daftar Pesanan</h5>
                <div class="order-item">
                    @foreach($carts as $cart)
                        <div class="order-card">
                            <!-- Gambar Menu -->
                            <div class="order-image">
                                <img src="{{ asset('storage/' . $cart->menuItem->image) }}" alt="{{ $cart->menuItem->name }}" class="img-fluid" onerror="this.src='/path/to/default-image.jpg'">
                            </div>
                            <!-- Detail Menu -->
                            <div class="order-details">
                                <h5>{{ $cart->menuItem->name }}</h5>
                                <p>Harga: Rp {{ number_format($cart->menuItem->price, 0, ',', '.') }}</p>
                                <p>Jumlah: {{ $cart->quantity }}</p>
                                <p>Total: Rp {{ number_format($cart->menuItem->price * $cart->quantity, 0, ',', '.') }}</p>
                                <!-- Menampilkan Kustomisasi -->
                                @if($cart->level_pedas || $cart->panas_dingin || $cart->level_es || $cart->manis || $cart->notes)
                                    <div class="customizations">
                                        <h6>Kustomisasi:</h6>
                                        @if($cart->level_pedas) <p>Level Pedas: {{ $cart->level_pedas }}</p> @endif
                                        @if($cart->panas_dingin) <p>Suhu: {{ $cart->panas_dingin }}</p> @endif
                                        @if($cart->level_es) <p>Level Es: {{ $cart->level_es }}</p> @endif
                                        @if($cart->manis) <p>Level Manis: {{ $cart->manis }}</p> @endif
                                        @if($cart->notes) <p>Catatan: {{ $cart->notes }}</p> @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
                <!-- Total Keseluruhan -->
                <div class="order-summary">
                    <p style="font-weight: bold; font-size: 18px;">Total Pembayaran: Rp {{ number_format($carts->sum(function($cart) { return $cart->menuItem->price * $cart->quantity; }), 0, ',', '.') }}</p>
                </div>
            </div>
            <button type="submit" id="checkout-button" class="btn btn-prima">Pesan</button>
        </form>
    @endif
    <!-- Modal untuk QRIS -->
    <div id="qrisModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pembayaran QRIS</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Silahkan klik tombol "Bayar QRIS" untuk pembayaran QRIS.</p>
                    <!-- Konten terkait Midtrans atau tombol bayar dapat ditambahkan di sini -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-prima" onclick="submitPayment()">Bayar QRIS</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script>
        document.getElementById('checkout-button').addEventListener('click', function (event) {
            event.preventDefault(); // Mencegah pengiriman form default

            const paymentMethod = document.getElementById('payment_method').value;

            if (paymentMethod === 'qris') {
                // Tampilkan modal QRIS
                $('#qrisModal').modal('show');
            } else {
                // Jika pembayaran tunai, kirim form
                document.querySelector('form').submit();
            }
        });

        // Fungsi untuk menutup modal
        function closeModal() {
            document.getElementById('qrisModal').classList.add('d-none');
        }

        // Fungsi untuk submit payment
        function submitPayment() {
            const formData = new FormData(document.querySelector('form'));

            // Ambil subtotal dari form atau kalkulasi di sini, pastikan angka
            const subtotal = parseFloat(document.getElementById('subtotal').value);  // Ambil nilai subtotal yang sudah dihitung

            if (isNaN(subtotal) || subtotal <= 0) {
                console.error('Subtotal tidak valid.');
                return;
            }

            // Kirim data ke server untuk mendapatkan Snap Token
            fetch('{{ route("checkout.getSnapToken") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    customer_name: formData.get('customer_name'),
                    subtotal: subtotal
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.snap_token) {
                    // Panggil modal Snap Midtrans
                    window.snap.pay(data.snap_token, {
                        onSuccess: function (result) {
                            console.log('Payment success:', result);
                            // Redirect ke halaman sukses
                            window.location.href = `/checkout/success/${data.order_id}`;
                        },
                        onPending: function (result) {
                            console.log('Payment pending:', result);
                        },
                        onError: function (result) {
                            console.error('Payment error:', result);
                        },
                        onClose: function () {
                            console.log('Payment popup closed.');
                        }
                    });
                } else {
                    console.error('Snap token tidak ditemukan.');
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>

</body>
</html>
