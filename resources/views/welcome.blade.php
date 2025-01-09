<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Selamat Datang</title>
    <!-- Link CSS Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <!-- Link CSS Kustom -->
    <link href="{{ asset('css/customer.css') }}" rel="stylesheet">
    {{-- Font CSS --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.css">
    <link rel="icon" type="image/png" href="{{ asset('images/numani.png') }}" sizes="32x32">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.27/dist/sweetalert2.min.js"></script>
</head>
</head>
<body>
    {{-- Navbar Start --}}
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container-fluid">
            <!-- Mengganti teks dengan gambar -->
            <a class="navbar-brand me-auto" href="#">
                <img src="/images/numani2.png" alt="Numani Coffee & Kitchen" style="height: 60px;">
            </a>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Numani Coffee</h5>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 active" aria-current="page" href="#menu">Menu</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Kategori Menu</a>
                            <ul class="dropdown-menu">
                                @foreach ($categories as $category)
                                <li class="nav-item">
                                    <a class="nav-link dropdown-item" href="#{{ $category->slug }}">{{ $category->name }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#" data-bs-toggle="modal" data-bs-target="#cartModal" id="cartLink">Keranjang</a>
                        </li>
                    </ul>
                </div>
            </div>
            <button class="navbar-toggler pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    {{-- Navbar End --}}

    {{-- Hero Section Start --}}
    <section id="hero" class="hero-section">
        <div class="hero-section">
            <img src="/images/banner.png" alt="Banner" class="hero-image">
        </div>
    </section>
    {{-- Hero Section End --}}

    {{-- Menu Section Start --}}
    <section id="menu">
        <div class="container my-4">
            <a class="navbar-brand me-auto" href="#">Numani <span>Kitchen & Coffee</span></a>
            <div style="border: 2px solid #D0AA11; border-radius: 15px; padding: 10px; margin: 10px; text-align: center; background-color: #f0de97; box-shadow: #d1b235;">
                <p id="datetime" style="margin: 5px 0 0; font-size: 1.2em; color: black;"></p>
            </div>
            <!-- Menu -->
            @foreach ($categories as $category)
                <section id="{{ $category->slug }}" class="mb-4">
                    <h3>{{ $category->name }}</h3>
                    <div class="menu-grid">
                        @forelse ($category->menuItems as $menu)
                                <div id="menu-{{ strtolower($menu->name) }}" class="card border-0 shadow-sm" data-name="{{ strtolower($menu->name) }}">
                                    <div class="card-img-top-wrapper mb-4">
                                        <img src="{{ asset('storage/' . $menu->image) }}" class="card-img-top" alt="{{ $menu->name }}">
                                    </div>
                                    <div class="card-body p-3">
                                        <h5 class="card-menu">{{ $menu->name }}</h5>
                                        <p class="card-text text-muted fs-6">Rp {{ number_format($menu->price, 0, ',', '.') }}</p>
                                        <button class="btn btn-prima w-100" data-bs-toggle="modal" data-bs-target="#menuModal{{ $menu->id }}">Tambah</button>
                                    </div>
                                </div>
                        @empty
                            <p class="text-muted">No items available in this category.</p>
                        @endforelse
                    </div>
                </section>
            @endforeach
        </div>

        <!-- Modal Detail -->
        @foreach ($categories as $category)
            @foreach ($category->menuItems as $menu)
                <div class="modal fade" id="menuModal{{ $menu->id }}" tabindex="-1" aria-labelledby="menuModalLabel{{ $menu->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="menuModalLabel{{ $menu->id }}">{{ $menu->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid mb-3" alt="{{ $menu->name }}">
                                <h5 class="card-menu">{{ $menu->name }}</h5>
                                <h6>Harga: Rp {{ number_format($menu->price, 0, ',', '.') }}</h6>
                                <p>{{ $menu->description }}</p>
                                <p>Total: <span id="totalPrice{{ $menu->id }}">{{ number_format($menu->price, 0, ',', '.') }}</span></p>

                                <!-- Opsi Kustomisasi -->
                                @if ($category->name === 'Menu Minuman')
                                    <div class="mb-3">
                                        <label for="panas_dingin{{ $menu->id }}" class="form-label">Suhu</label>
                                        <select id="panas_dingin{{ $menu->id }}" class="form-select" onchange="toggleIceOptions({{ $menu->id }})">
                                            <option value="" selected disabled>Pilih Suhu</option>
                                            <option value="Panas">Panas</option>
                                            <option value="Dingin">Dingin</option>
                                        </select>
                                    </div>

                                    <!-- Kadar Es -->
                                    <div class="mb-3 ice-level-container" id="iceLevelContainer{{ $menu->id }}">
                                        <label for="iceLevel{{ $menu->id }}" class="form-label">Level Es</label>
                                        <select id="iceLevel{{ $menu->id }}" class="form-select">
                                            <option value="" selected disabled>Pilih Level Es</option>
                                            <option value="Sedikit">Es Sedikit</option>
                                            <option value="Normal">Es Normal</option>
                                            <option value="Ekstra">Ekstra Es</option>
                                        </select>
                                    </div>

                                    <!-- Kadar Gula -->
                                    <div class="mb-3">
                                        <label for="manisLevel{{ $menu->id }}" class="form-label">Level Gula</label>
                                        <select id="manisLevel{{ $menu->id }}" class="form-select">
                                            <option value="" selected disabled>Pilih level gula</option>
                                            <option value="Sedikit">Sedikit Manis</option>
                                            <option value="Normal">Manis</option>
                                            <option value="Sangat Manis">Sangat Manis</option>
                                        </select>
                                    </div>
                                @elseif ($category->name === 'Menu Paket' || $category->name === 'Menu Satuan')
                                    <div class="mb-3">
                                        <label for="spiceLevel{{ $menu->id }}" class="form-label">Level Pedas</label>
                                        <select id="spiceLevel{{ $menu->id }}" class="form-select">
                                            <option value="" selected disabled>Pilih level pedas</option>
                                            <option value="Biasa">Pedas Biasa</option>
                                            <option value="Sedang">Pedas Sedang</option>
                                            <option value="Ekstra">Pedas Ekstra</option>
                                        </select>
                                    </div>
                                @endif

                                <!-- Catatan -->
                                <div class="mb-3">
                                    <label for="note{{ $menu->id }}" class="form-label">Catatan</label>
                                    <textarea id="note{{ $menu->id }}" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity({{ $menu->id }}, {{ $menu->price }}, -1)">-</button>
                                    <input type="number" id="quantity{{ $menu->id }}" class="form-control text-center mx-2" value="1" min="1" style="width: 60px;" oninput="updateTotal({{ $menu->id }}, {{ $menu->price }})">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity({{ $menu->id }}, {{ $menu->price }}, 1)">+</button>
                                </div>
                                <button type="button" class="btn btn-prima add-to-cart" data-menu-item-id="{{ $menu->id }}">
                                    Pesan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
        @foreach ($categories as $category)
            @foreach ($category->menuItems as $menu)
                <div class="modal fade" id="menuModalEdit{{ $menu->id }}" tabindex="-1" aria-labelledby="menuModalLabel{{ $menu->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="menuModalLabel{{ $menu->id }}">{{ $menu->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modalEdit" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <img src="{{ asset('storage/' . $menu->image) }}" class="img-fluid mb-3" alt="{{ $menu->name }}">
                                <h5 class="card-menu">{{ $menu->name }}</h5>
                                <h6>Harga: Rp {{ number_format($menu->price, 0, ',', '.') }}</h6>
                                <p>{{ $menu->description }}</p>
                                <p>Total: <span id="totalPrice{{ $menu->id }}">{{ number_format($menu->price, 0, ',', '.') }}</span></p>

                                <!-- Opsi Kustomisasi -->
                                @if ($category->name === 'Menu Minuman')
                                    <div class="mb-3">
                                        <label for="panas_dingin{{ $menu->id }}" class="form-label">Suhu</label>
                                        <select id="panas_dingin{{ $menu->id }}" class="form-select" onchange="toggleIceOptions({{ $menu->id }})">
                                            <option value="" selected disabled>Pilih Suhu</option>
                                            <option value="Panas">Panas</option>
                                            <option value="Dingin">Dingin</option>
                                        </select>
                                    </div>

                                    <!-- Kadar Es -->
                                    <div class="mb-3 ice-level-container" id="iceLevelContainer{{ $menu->id }}">
                                        <label for="iceLevel{{ $menu->id }}" class="form-label">Level Es</label>
                                        <select id="iceLevel{{ $menu->id }}" class="form-select">
                                            <option value="" selected disabled>Pilih Level Es</option>
                                            <option value="Sedikit">Es Sedikit</option>
                                            <option value="Normal">Es Normal</option>
                                            <option value="Ekstra">Ekstra Es</option>
                                        </select>
                                    </div>

                                    <!-- Kadar Gula -->
                                    <div class="mb-3">
                                        <label for="manisLevel{{ $menu->id }}" class="form-label">Level Gula</label>
                                        <select id="manisLevel{{ $menu->id }}" class="form-select">
                                            <option value="" selected disabled>Pilih level gula</option>
                                            <option value="Sedikit">Sedikit Manis</option>
                                            <option value="Normal">Manis</option>
                                            <option value="Sangat Manis">Sangat Manis</option>
                                        </select>
                                    </div>
                                @elseif ($category->name === 'Menu Paket' || $category->name === 'Menu Satuan')
                                    <div class="mb-3">
                                        <label for="spiceLevel{{ $menu->id }}" class="form-label">Level Pedas</label>
                                        <select id="spiceLevel{{ $menu->id }}" class="form-select">
                                            <option value="" selected disabled>Pilih level pedas</option>
                                            <option value="Biasa">Pedas Biasa</option>
                                            <option value="Sedang">Pedas Sedang</option>
                                            <option value="Ekstra">Pedas Ekstra</option>
                                        </select>
                                    </div>
                                @endif

                                <!-- Catatan -->
                                <div class="mb-3">
                                    <label for="note{{ $menu->id }}" class="form-label">Catatan</label>
                                    <textarea id="note{{ $menu->id }}" class="form-control" rows="3" placeholder="Catatan tambahan..."></textarea>
                                </div>
                            </div>

                            <div class="modal-footer d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity({{ $menu->id }}, {{ $menu->price }}, -1)">-</button>
                                    <input type="number" id="quantity{{ $menu->id }}" class="form-control text-center mx-2" value="1" min="1" style="width: 60px;" oninput="updateTotal({{ $menu->id }}, {{ $menu->price }})">
                                    <button class="btn btn-outline-secondary" onclick="changeQuantity({{ $menu->id }}, {{ $menu->price }}, 1)">+</button>
                                </div>
                                <button type="button" class="btn btn-prima add-to-cart" data-menu-item-id="{{ $menu->id }}">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endforeach
    </section>

    <!-- Modal Keranjang -->
    <div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cartModalLabel">Daftar Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="cart-items-container">
                        <!-- Daftar item keranjang akan dimuat di sini melalui AJAX -->
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="{{ route('checkout.index') }}" class="btn btn-prima">Konfirmasi Pesanan</a>
                </div>
            </div>
        </div>
    </div>



    {{-- Menu Section End --}}

    <!-- Script JS Bootstrap -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(window).on("scroll", function () {
            if ($(this).scrollTop() > 0) { // Cek jika halaman digulir
                $(".navbar").addClass("scrolled");
            } else {
                $(".navbar").removeClass("scrolled");
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.nav-link').on('click', function(event) {
                event.preventDefault();
                var target = $(this).attr('href');
                var targetElement = document.querySelector(target);
                var targetPosition = targetElement.getBoundingClientRect().top + window.scrollY;

                $('html, body').animate({
                    scrollTop: targetPosition
                }, 1000);
            });
        });
    </script>
    <script>
        const dateTimeElement = document.getElementById('datetime');

        function updateDate() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'short', year: 'numeric' };
            const formattedDate = now.toLocaleDateString('id-ID', options)
                                      .replace('.', ''); // Menghapus titik pada nama bulan
            dateTimeElement.textContent = formattedDate;
        }

        updateDate();
    </script>
    <script>
        function updateTotal(menuId, price) {
            // Ambil nilai quantity
            const quantity = document.getElementById(`quantity${menuId}`).value;

            // Hitung total harga
            const totalPrice = price * quantity;

            // Format ke dalam format harga
            const formattedPrice = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
            }).format(totalPrice).replace('IDR', 'Rp').trim();

            // Tampilkan total harga yang sudah diperbarui
            document.getElementById(`totalPrice${menuId}`).innerText = formattedPrice;
        }

        function changeQuantity(menuId, price, delta) {
            const quantityInput = document.getElementById(`quantity${menuId}`);
            let quantity = parseInt(quantityInput.value);

            // Update quantity
            quantity = isNaN(quantity) ? 1 : Math.max(1, quantity + delta); // Minimal 1
            quantityInput.value = quantity;

            // Update total
            updateTotal(menuId, price);
        }

    </script>
    <script>
        $(document).ready(function () {
            // Script untuk menambahkan item ke keranjang
            $(document).on('click', '.add-to-cart', function () {
                var menuId = $(this).data('menu-item-id');
                var quantity = $('#quantity' + menuId).val();
                var spiceLevel = $('#spiceLevel' + menuId).val();
                var panas_dingin = $('#panas_dingin' + menuId).val();
                var iceLevel = $('#iceLevel' + menuId).val();
                var manisLevel = $('#manisLevel' + menuId).val();
                var note = $('#note' + menuId).val();

                $.ajax({
                    url: '{{ route('cart.add') }}', // Route untuk menambahkan ke keranjang
                    type: 'POST',
                    data: {
                        menu_items_id: menuId,
                        quantity: quantity,
                        level_pedas: spiceLevel,
                        panas_dingin: panas_dingin,
                        level_es: iceLevel,
                        manis: manisLevel,
                        notes: note,
                        _token: '{{ csrf_token() }}' // Jangan lupa menyertakan CSRF token
                    },
                    success: function (response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: 'Menu berhasil ditambahkan ke keranjang.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                            updateCartCount(); // Perbarui jumlah item di keranjang
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal menambahkan menu ke keranjang.',
                            });
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Terjadi Kesalahan',
                            text: 'Terjadi kesalahan saat menambahkan ke keranjang.',
                        });
                    }
                });
            });

            // Fungsi untuk memperbarui jumlah item di keranjang di navbar
            function updateCartCount(cartId, newQuantity) {
                $.ajax({
                    url: '/cart/' + cartId,  // Sesuaikan URL
                    type: 'PUT',
                    data: {
                        quantity: newQuantity,  // Data yang dikirimkan untuk update
                        _token: '{{ csrf_token() }}'  // Token CSRF untuk keamanan
                    },
                    success: function (response) {
                        if (response.count !== undefined) {
                            $('#cart-count').text(response.count); // Update elemen dengan id 'cart-count'
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            $(document).ready(function () {
                // Delegasi event untuk elemen dinamis
                $(document).on('change', '.form-select[id^="panas_dingin"]', function () {
                    const selectedValue = $(this).val();
                    const menuId = $(this).attr('id').replace('panas_dingin', '');

                    if (selectedValue === 'Dingin') {
                        $(`#iceLevelContainer${menuId}`).show();
                    } else {
                        $(`#iceLevelContainer${menuId}`).hide();
                    }
                });

                // Sembunyikan kadar es secara default
                $('.ice-level-container').hide();
            });

            // Saat modal keranjang dibuka, ambil data keranjang dan tampilkan
            $(document).on('click', '#cartLink', function (e) {
                e.preventDefault();

                $.ajax({
                    url: '{{ route('cart.getItems') }}',
                    type: 'GET',
                    success: function (response) {
                        $('#cart-items-container').empty();

                        if (response.carts.length > 0) {
                            response.carts.forEach(function (cart) {
                                let customizations = '';
                                if (cart.level_pedas) customizations += `<p>Level Pedas: ${cart.level_pedas}</p>`;
                                if (cart.panas_dingin) customizations += `<p>Suhu: ${cart.panas_dingin}</p>`;
                                if (cart.level_es) customizations += `<p>Level Es: ${cart.level_es}</p>`;
                                if (cart.manisLevel) customizations += `<p>Level Gula: ${cart.manisLevel}</p>`;
                                if (cart.notes) customizations += `<p>Catatan: ${cart.notes}</p>`;

                                const itemHtml = `
                                    <div class="card mb-3">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <img src="{{ asset('storage') }}/${cart.menu_item.image}" class="img-fluid rounded-start" alt="${cart.menu_item.name}" onerror="this.src='path/to/default-image.jpg'">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title" style="margin-bottom: 5px;">${cart.menu_item.name}</h5>
                                                    <p>Harga: Rp ${cart.menu_item.price.toLocaleString('id-ID')}</p>
                                                    <p>Jumlah: ${cart.quantity}</p>
                                                    <p>Total: Rp ${(cart.menu_item.price * cart.quantity).toLocaleString('id-ID')}</p>
                                                    <div>
                                                        <h6>Kustomisasi:</h6>
                                                        ${customizations || '<p>Tidak ada kustomisasi</p>'}
                                                    </div>
                                                    <button class="btn btn-danger btn-sm delete-cart" data-cart-id="${cart.id}">Hapus</button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-warning edit-cart btn-sm"
                                                        data-cart-id="${cart.id}"
                                                        data-menu-id="${cart.menu_item.id}"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#menuModal${cart.menu_item.id}">
                                                        Ubah
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>`;
                                $('#cart-items-container').append(itemHtml);
                            });
                        } else {
                            $('#cart-items-container').html('<p class="text-muted">Keranjang kosong.</p>');
                        }
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('Gagal memuat keranjang.');
                    }
                });
            });

            // Fungsi untuk menghapus item dari keranjang
            $(document).on('click', '.delete-cart', function () {
                const cartId = $(this).data('cart-id'); // Ambil ID cart

                // Ganti confirm dengan SweetAlert
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Item ini akan dihapus dari keranjang!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Proses hapus item
                        $.ajax({
                            url: `/cart/` + cartId, // Gunakan URL dengan ID cart
                            type: 'DELETE', // DELETE method
                            data: {
                                _token: '{{ csrf_token() }}',
                            },
                            success: function (response) {
                                if (response.success) {
                                    Swal.fire('Terhapus!', response.message, 'success'); // Menampilkan SweetAlert saat sukses
                                    updateCartCount(); // Perbarui jumlah item di navbar
                                    $('#cartLink').click(); // Reload keranjang
                                } else {
                                    Swal.fire('Gagal!', 'Gagal menghapus item.', 'error'); // Menampilkan error SweetAlert
                                }
                            },
                            error: function (xhr) {
                                console.error(xhr.responseText);
                                Swal.fire('Error!', 'Terjadi kesalahan saat menghapus item.', 'error'); // Menampilkan error SweetAlert
                            }
                        });
                    }
                });
            });

            // Fungsi untuk memperbarui total harga di modal
            function updateTotal(menuId) {
                const price = $('#price' + menuId).data('price'); // Ambil harga menu dari data attribute
                const quantity = parseInt($('#quantity' + menuId).val(), 10);
                const total = price * quantity;
                $('#totalPrice' + menuId).text(total.toLocaleString('id-ID'));
            }
        });
    </script>
    <script>
        $(document).on('click', '.edit-cart', function () {
            const cartId = $(this).data('cart-id');
            const menuId = $(this).data('menu-id');

            // Ambil data dari server untuk mengisi modal
            $.ajax({
                url: `/cart/${cartId}/edit`,
                type: 'GET',
                success: function (response) {
                    const cart = response.cart;

                    // Isi modal dengan data yang diambil
                    $(`#quantity${menuId}`).val(cart.quantity);
                    $(`#panas_dingin${menuId}`).val(cart.panas_dingin || '');
                    $(`#iceLevel${menuId}`).val(cart.level_es || '');
                    $(`#manisLevel${menuId}`).val(cart.level_gula || '');
                    $(`#spiceLevel${menuId}`).val(cart.level_pedas || '');
                    $(`#note${menuId}`).val(cart.notes || '');
                },
                error: function () {
                    alert('');
                },
            });
        });

        // Simpan perubahan ke keranjang
        $(document).on('click', '.add-to-cart', function () {
            const menuId = $(this).data('menu-item-id');
            const cartId = $(this).data('cart-id');
            const updatedData = {
                quantity: parseInt($(`#quantity${menuId}`).val(), 10),
                panas_dingin: $(`#panas_dingin${menuId}`).val(),
                level_es: $(`#iceLevel${menuId}`).val(),
                level_gula: $(`#manisLevel${menuId}`).val(),
                level_pedas: $(`#spiceLevel${menuId}`).val(),
                notes: $(`#note${menuId}`).val(),
            };

            console.log('Updated Data:', updatedData); // Debugging

            // Kirim data perubahan ke server
            $.ajax({
                url: `/cart/${cartId}`,
                type: 'PUT',
                data: updatedData,
                success: function () {
                    alert('Data keranjang berhasil diperbarui.');
                    // Refresh daftar keranjang
                    $('#cartLink').trigger('click');
                },
                error: function (xhr) {
                    console.error('Error:', xhr.responseText);
                },
            });
        });
    </script>
</body>
</html>
