@extends('admin.layout')

@section('content')
<div class="navbar fixed-top" style="background-color: #f8f9fa; padding: 15px 20px; border-bottom: 1px solid #ddd; z-index: 1050;">
    <h2 style="margin: 0;">Panel <span style="color: #d0aa11">Admin</span> - <span>Menu</span></h2>
</div>
<a href="{{ route('admin.menus.create') }}" class="btn" style="background-color: #d0aa11; color: white; margin-top: 100px">Tambah Item Menu</a>
<div class="container" style="margin-top: 40px; padding: 0 20px;">
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menuItems as $item)
                    <tr>
                        <td>{{ $item->name }}</td>
                        <td>{{ $item->category->name }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->stock }}</td>
                        <td><img src="{{ asset('storage/' . $item->image) }}" width="50" alt="{{ $item->name }}"></td>
                        <td>
                            <div class="action-buttons">
                                <form action="{{ route('admin.menus.edit', $item) }}" method="GET" style="margin: 0;">
                                    <button type="submit" style="background: #134B70; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                                        Ubah
                                    </button>
                                </form>

                                <button class="btn-delete" data-id="{{ $item->id }}" style="background: #990000; color: white; padding: 8px 12px; border: none; border-radius: 8px; font-size: 14px; cursor: pointer;">
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">No menu items available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const url = `/admin/menus/${id}`;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Item menu ini akan dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Kirim request POST untuk menghapus item
                        fetch(url, {
                            method: 'DELETE',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Dihapus!',
                                    'Item menu telah dihapus.',
                                    'success'
                                ).then(() => {
                                    // Hapus baris tabel dari tampilan
                                    button.closest('tr').remove();
                                });
                            } else {
                                Swal.fire(
                                    'Gagal!',
                                    'Item menu tidak dapat dihapus.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Gagal!',
                                'Terjadi kesalahan, coba lagi nanti.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    });
</script>
@endsection
