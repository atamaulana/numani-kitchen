<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- Boxicons -->
	<link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- Include Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

	<!-- My CSS -->
	<link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="icon" type="image/png" href="{{ asset('images/numani.png') }}" sizes="32x32">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.23/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

	<title>@yield('title', 'Numani Coffee')</title>
</head>
<body>
	<!-- SIDEBAR -->
        <div class="container">
            <div class="sidebar">
                <div class="header">
                    <div class="list-item">
                        <a href="#" class="brand">
                            <span class="description-header" style="margin-bottom: 20px"> Panel Admin</span>
                            <span class="description-header" style="color: #D0AA11; font-size: 15px">Numani Coffee & Kitchen</span>
                        </a>
                    </div>
                    <div class="illustration">
                        <img src="/images/numani.png" alt="Illustration" width="200" height="200">
                    </div>
                </div>
                <div class="main">
                    <div class="list-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class='bx bxs-dashboard icon' style="color: white"></i>
                            <span class="description">Dashboard</span>
                        </a>
                    </div>
                    <div class="list-item {{ request()->routeIs('admin.menus.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.menus.index') }}">
                            <i class='bx bxs-shopping-bag-alt icon' style="color: white"></i>
                            <span class="description">Menu</span>
                        </a>
                    </div>
                    <div class="list-item {{ request()->routeIs('admin.transaksi.index') ? 'active' : '' }}">
                        <a href="{{ route('admin.transaksi.index') }}">
                            <i class='bx bxs-doughnut-chart icon' style="color: white"></i>
                            <span class="description">Transaksi</span>
                        </a>
                    </div>
                    <div class="list-item">
                        <a href="#" onclick="confirmLogout(event)" class="logout">
                            <i class='bx bxs-log-out-circle icon' style="color: white"></i>
                            <span class="description">Keluar</span>
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </div>
            </div>
        </div>
	<!-- SIDEBAR -->

	<!-- CONTENT -->
	<section id="content">
		<!-- MAIN -->
		<main>
			@yield('content')
		</main>
		<!-- MAIN -->
	</section>
	<!-- CONTENT -->

	<script src="script.js"></script>
    <!-- Script ke Bootstrap JS dan Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.2/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dompurify@2.4.0/dist/purify.min.js"></script>
    <script>
        function confirmLogout(event) {
            event.preventDefault(); // Mencegah tautan langsung dijalankan

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda akan keluar dari aplikasi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit(); // Mengirimkan form jika pengguna mengkonfirmasi
                }
            });
        }
    </script>
</body>
</html>
