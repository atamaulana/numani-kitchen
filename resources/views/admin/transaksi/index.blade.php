@extends('admin.layout')

@section('content')
<div class="navbar fixed-top" style="background-color: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #ddd; z-index: 1050;">
    <h2 style="margin: 0;">Panel <span style="color: #d0aa11">Admin</span> - <span>Transaksi</span></h2>
</div>
{{-- {{ route('transaksi.export') }} --}}
<div class="containerul" style="margin-top: 100px">
    <form action="" method="GET">
        <div class="form-group row">
            <label for="start_date">Dari Tanggal:</label>
            <input type="date" name="start_date" id="start_date" class="form-control">
        </div>
        <div class="form-group row">
            <label for="end_date">Hingga Tanggal:</label>
            <input type="date" name="end_date" id="end_date" class="form-control">
        </div>
        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 20px;">
            <button type="button" class="btn btn-success" id="exportSummaryPdf" style="background-color: #D0AA11; border-radius: 20px; color: white; padding: 10px 20px; border: none; display: inline-block; max-width: 300px">
                UNDUH IKHTISAR TRANSAKSI (PDF)
            </button>
            <button type="button" class="btn btn-primary" id="exportDetailsPdf" style="background: #638C6D; border-radius: 20px; color: white; padding: 10px 20px; border: none; cursor: pointer; display: inline-block; max-width: 300px">
                UNDUH DETAIL TRANSAKSI (PDF)
            </button>
        </div>
        <div class="mb-3" style="margin-top: 10px">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari Kode Pesanan...">
        </div>
    </form>
    <div class="container" style="margin-top: 40px; padding: 0 20px;">
        <div class="table-container">
            <table id="detailTable" class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pesanan</th>
                        <th>Nama</th>
                        <th>Hp/Telp</th>
                        <th>No Meja</th>
                        <th>Lantai</th>
                        <th>Metode Pembayaran</th>
                        <th>Status</th>
                        <th>Pesanan</th>
                        <th>Kustomisasi</th>
                        <th>Total Pesanan</th>
                        <th>Waktu</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($checkouts as $index => $checkout)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $checkout->order_code }}</td>
                            <td>{{ $checkout->customer_name }}</td>
                            <td>{{ $checkout->phone_number }}</td>
                            <td>{{ $checkout->table_number }}</td>
                            <td>{{ $checkout->floor_number }}</td>
                            <td>{{ ucfirst($checkout->payment_method) }}</td>
                            <td>
                                @if($checkout->status === 'unpaid')
                                    <button type="button"
                                            class="btn btn-sm btn-danger"
                                            onclick="openPaymentModal({{ $checkout->id }}, {{ $checkout->subtotal }})">
                                        Belum Terbayar
                                    </button>
                                @else
                                    <span class="badge bg-success larger-badge">Terbayar</span>
                                @endif
                            </td>
                            <td>
                                <ul style="list-style-type: none; padding: 0; margin: 0;">
                                    @foreach($checkout->items as $item)
                                        <li>
                                            {{ $item->quantity }}x {{ $item->menu_name }}
                                            - Rp {{ number_format($item->menu_price, 0, ',', '.') }}
                                            (Total: Rp {{ number_format($item->total_price, 0, ',', '.') }})
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td style="padding-top: 0">
                                @foreach($checkout->items as $item)
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
                                @endforeach
                            </td>
                            <td>Rp {{ number_format($checkout->subtotal, 0, ',', '.') }}</td>
                            <td>{{ $checkout->created_at }}</td>
                            <td>
                                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#detailModal{{ $checkout->id }}" style="background-color: #134B70">
                                    STRUK
                                </button>
                                <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $checkout->id }}" style="background-color: #990000">HAPUS</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="text-center">Tidak ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Input Pembayaran -->
<div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="paymentForm" method="POST" action="{{ route('admin.transaksi.pay') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header ">
                    <h5 class="modal-title justify-content-center" id="paymentModalLabel">Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="transactionId" name="id">
                    <div class="mb-3">
                        <label for="subtotal" class="form-label">Total Pembayaran</label>
                        <input type="text" id="subtotal" class="form-control" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="amountPaid" class="form-label">Uang Diterima</label>
                        <input type="number" id="amountPaid" name="amount_paid" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="change" class="form-label">Uang Kembali</label>
                        <input type="text" id="change" class="form-control" readonly>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-custom">Bayar</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Modal Struk -->
@foreach ($checkouts as $checkout)
    <div class="modal fade" id="detailModal{{ $checkout->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $checkout->id }}" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel{{ $checkout->id }}">Struk Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="receipt">
                        <div class="receipt-header">
                            <h4 class="text-center">Numani Coffee & Kitchen</h4>
                            <p class="text-center">Jl. Cibanteng No.36 2, RT.2/RW.1, Koja, Jkt Utara, DKI Jakarta</p>
                            <p class="text-center">Telp: 0857-3118-0094</p>
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
                            <h5>Pesanan Anda:</h5>
                            <div class="order-body">
                                <div class="order-summary">
                                    <div class="order-header">
                                        <div class="order-col">Menu</div>
                                        <div class="order-col">Harga</div>
                                        <div class="order-col">Total Harga</div>
                                        <div class="order-col">Kustomisasi</div>
                                    </div>
                                    @foreach($checkout->items as $item)
                                    <div class="order-row">
                                        <div class="order-col">{{ $item->quantity }} {{ $item->menu_name }}</div>
                                        <div class="order-col">Rp {{ number_format($item->menu_price, 0, ',', '.') }}</div>
                                        <div class="order-col">Rp {{ number_format($item->total_price, 0, ',', '.') }}</div>
                                        <div class="order-col" style="padding-top: 0;">
                                            <!-- Menampilkan kustomisasi item -->
                                            @if($item->level_pedas || $item->panas_dingin || $item->level_es || $item->manis || $item->note)
                                            <br>
                                            <small>
                                                @if($item->level_pedas) Level Pedas: {{ $item->level_pedas }}@endif
                                                @if($item->level_pedas && $item->panas_dingin) | @endif
                                                @if($item->panas_dingin) Suhu: {{ $item->panas_dingin }}@endif
                                                @if($item->panas_dingin && $item->level_es) | @endif
                                                @if($item->level_es) Level Es: {{ $item->level_es }}@endif
                                                @if($item->level_es && $item->manis) | @endif
                                                @if($item->manis) Manis: {{ $item->manis }}@endif
                                                @if($item->note) | Catatan: {{ $item->note }}@endif
                                            </small>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <hr>
                        </div>

                        <div class="receipt-footer">
                            <p><strong>Subtotal:</strong> Rp {{ number_format($checkout->subtotal, 0, ',', '.') }}</p>
                            <hr>
                            <p class="text-center">Terima Kasih atas kunjungan Anda!</p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-prima" onclick="printReceiptToPDF()">Cetak Struk</button>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script>
    function openPaymentModal(transactionId, subtotal) {
        // Set data ke modal
        document.getElementById('transactionId').value = transactionId;
        document.getElementById('subtotal').value = subtotal;
        document.getElementById('amountPaid').value = '';
        document.getElementById('change').value = '';

        // Tampilkan modal
        const paymentModal = new bootstrap.Modal(document.getElementById('paymentModal'));
        paymentModal.show();
    }

    // Hitung kembalian otomatis
    document.getElementById('amountPaid').addEventListener('input', function () {
        const subtotal = parseFloat(document.getElementById('subtotal').value);
        const amountPaid = parseFloat(this.value);
        const change = amountPaid - subtotal;

        document.getElementById('change').value = change >= 0 ? change : 'Uang tidak cukup';
    });
</script>
<script>
    function printReceiptToPDF() {
        // Mengambil konten struk
        var receiptContent = document.querySelector('.receipt').innerHTML;

        // Inisialisasi jsPDF
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();

        // Menambahkan konten struk ke dalam PDF
        doc.html(receiptContent, {
            callback: function (doc) {
                // Menyimpan PDF dengan nama "struk.pdf"
                doc.save('struk.pdf');
            },
            x: 10,
            y: 10
        });
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const csrfMetaTag = document.querySelector('meta[name="csrf-token"]');

        if (!csrfMetaTag) {
            console.error('CSRF token not found!');
            return;
        }

        const csrfToken = csrfMetaTag.getAttribute('content');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const url = `/checkout/delete/${id}`;

                // Menggunakan SweetAlert untuk konfirmasi
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Transaksi ini akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.message) {
                                Swal.fire(
                                    'Dihapus!',
                                    data.message,
                                    'success'
                                );
                                // Hapus baris tabel dari tampilan
                                this.closest('tr').remove();
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    }
                });
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const table = document.getElementById('detailTable');
        const tbody = table.querySelector('tbody');

        searchInput.addEventListener('input', function () {
            const filter = searchInput.value.toLowerCase();
            const rows = tbody.querySelectorAll('tr');
            let hasResults = false;

            rows.forEach(row => {
                const codeCell = row.cells[1]; // Kolom Kode Pesanan
                const originalText = codeCell.textContent;
                const lowerCaseText = originalText.toLowerCase();

                // Hapus highlight sebelumnya
                codeCell.innerHTML = originalText;

                if (filter === '') {
                    row.style.display = ''; // Tampilkan semua baris jika input kosong
                } else if (lowerCaseText.includes(filter)) {
                    // Highlight teks yang cocok
                    const startIndex = lowerCaseText.indexOf(filter);
                    const matchedText = originalText.substr(startIndex, filter.length);
                    const highlightedText = `<span class="highlight">${matchedText}</span>`;
                    const newText = originalText.replace(matchedText, highlightedText);

                    codeCell.innerHTML = newText;
                    row.style.display = ''; // Tampilkan baris
                    hasResults = true;
                } else {
                    row.style.display = 'none'; // Sembunyikan baris
                }
            });

            if (!hasResults && filter !== '') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Tidak ditemukan!',
                    text: 'Kode pesanan yang Anda cari tidak ditemukan.',
                    confirmButtonText: 'OK'
                });
            }
        });

        searchInput.addEventListener('keydown', function (e) {
            if (e.key === 'Enter') {
                const filter = searchInput.value.toLowerCase();
                const rows = tbody.querySelectorAll('tr');
                let found = false;

                rows.forEach(row => {
                    const codeCell = row.cells[1]; // Kolom Kode Pesanan
                    if (codeCell.textContent.toLowerCase().includes(filter) && filter !== '') {
                        found = true;
                        row.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        row.style.outline = '2px solid red'; // Highlight baris
                        setTimeout(() => (row.style.outline = ''), 2000);
                    }
                });

                if (!found) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Tidak ditemukan!',
                        text: 'Kode pesanan yang Anda cari tidak ditemukan.',
                        confirmButtonText: 'OK'
                    });
                }
            }
        });
    });
</script>
<script>
    // Ekspor file pertama: Summary
    document.getElementById('exportSummaryPdf').addEventListener('click', function () {
        Swal.fire({
            title: 'Unduh ikhtisar transaksi (PDF)',
            text: 'Apakah anda yakin ingin cetak ikhtisar transaksi menjadi PDF?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Cetak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Header PDF
                doc.text("Summary Transaksi", 14, 10);

                // Tabel
                doc.autoTable({
                    head: [['Kode Pesanan', 'Nama', 'No Telp', 'No Meja', 'No Lantai', 'Metode Pembayaran']],
                    body: Array.from(document.querySelectorAll('.table tbody tr')).map(row => {
                        const cells = row.children;
                        return [
                            cells[1].textContent.trim(), // Kode Pesanan
                            cells[2].textContent.trim(), // Nama
                            cells[3].textContent.trim(), // No Telp
                            cells[4].textContent.trim(), // No Meja
                            cells[5].textContent.trim(), // No Lantai
                            cells[6].textContent.trim()  // Metode Pembayaran
                        ];
                    }),
                    startY: 20,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [255, 255, 255],
                        textColor: [0, 0, 0],
                        fontSize: 10,
                        fontStyle: 'bold',
                    },
                    bodyStyles: {
                        fontSize: 8,
                        textColor: [0, 0, 0],
                    }
                });

                // Simpan file PDF
                doc.save('summary-transactions.pdf');
                Swal.fire('Sukses!', 'Ikhtisar Transaksi telah berhasil di cetak', 'success');
            }
        });
    });

    // Ekspor file kedua: Details
    document.getElementById('exportDetailsPdf').addEventListener('click', function () {
        // Validasi apakah tabel memiliki data
        const rows = document.querySelectorAll('#detailTable tbody tr');
        if (rows.length === 0) {
            Swal.fire('Error!', 'No data available to export.', 'error');
            return;
        }

        // Menyaring data berdasarkan start_date dan end_date jika ada
        const startDateElement = document.getElementById('start_date');
        const endDateElement = document.getElementById('end_date');

        const startDate = startDateElement ? startDateElement.value : ''; // Cek keberadaan elemen
        const endDate = endDateElement ? endDateElement.value : ''; // Cek keberadaan elemen

        const filteredRows = Array.from(rows).filter(row => {
            const dateText = row.cells[11]?.textContent.trim(); // Kolom Tgl/Waktu (asumsi kolom ke-12)
            if (!dateText) return false; // Jika tidak ada tanggal, jangan diproses

            const rowDate = new Date(dateText);
            const start = startDate ? new Date(startDate) : null;
            const end = endDate ? new Date(endDate) : null;

            // Periksa apakah tanggal baris sesuai dengan rentang yang diberikan
            if (start && rowDate < start) return false; // Tanggal lebih awal dari start_date
            if (end && rowDate > end) return false; // Tanggal lebih lama dari end_date

            return true; // Baris lolos filter
        });

        if (filteredRows.length === 0) {
            Swal.fire('Error!', 'No data matches the selected date range.', 'error');
            return;
        }

        Swal.fire({
            title: 'Unduh detail transkasi (PDF)',
            text: 'Apakah anda yakin ingin cetak detail transaksi menjadi PDF?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Cetak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                const { jsPDF } = window.jspdf;
                const doc = new jsPDF();

                // Header PDF
                doc.text("Details Transaksi", 14, 10);

                // Tabel
                doc.autoTable({
                    head: [['Kode Pesanan', 'Pesanan', 'Kustomisasi', 'Subtotal', 'Tgl/Waktu']],
                    body: filteredRows.map(row => {
                        const cells = row.children;
                        const orders = Array.from(cells[8].querySelectorAll('ul li')).map(order => order.textContent.trim()).join(', ');
                        const customizations = Array.from(cells[9].querySelectorAll('small')).map(cust => cust.textContent.trim()).join(', ');

                        return [
                            cells[1].textContent.trim(), // Kode Pesanan
                            orders,                     // Pesanan
                            customizations,             // Kustomisasi
                            cells[10].textContent.trim(), // Subtotal
                            cells[11].textContent.trim()  // Tgl/Waktu
                        ];
                    }),
                    startY: 20,
                    theme: 'grid',
                    headStyles: {
                        fillColor: [255, 255, 255],
                        textColor: [0, 0, 0],
                        fontSize: 10,
                        fontStyle: 'bold',
                    },
                    bodyStyles: {
                        fontSize: 8,
                        textColor: [0, 0, 0],
                    }
                });

                // Simpan file PDF
                doc.save('details-transactions.pdf');
                Swal.fire('Success!', 'Details PDF has been exported.', 'success');
            }
        });
    });
</script>

@endsection
