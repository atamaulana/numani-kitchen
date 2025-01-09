<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [Controller::class, 'index'])->name('welcome');

// Login routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

// Admin dashboard routes
Route::middleware(['auth:admin', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // CRUD untuk kategori (menggunakan resource controller)
    Route::get('dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('categories', AdminController::class);
    // Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    // CRUD untuk menu items (menggunakan resource controller)
    Route::resource('menu-items', MenuItemController::class);

    // CRUD untuk menu (MenuController)
    Route::resource('menus', MenuController::class);

    // Orders routes
    Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');

    // Transaksi routes
    Route::get('transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::post('transaksi/{id}/update-status', [TransaksiController::class, 'updateStatus'])->name('admin.transaksi.update-status');
    Route::post('transaksi/pay', [TransaksiController::class, 'payTransaction'])->name('transaksi.pay');
    Route::get('/get-transactions', [TransaksiController::class, 'getTransactions']);

});

// Customer-related routes


// Route untuk keranjang
Route::prefix('cart')->group(function () {
    // Rute untuk menambah item ke keranjang
    Route::post('add', [CartController::class, 'addToCart'])->name('cart.add');

    // Rute untuk mendapatkan item-item di keranjang
    Route::get('get-items', [CartController::class, 'getCartItems'])->name('cart.getItems');

    // Rute untuk mendapatkan detail item keranjang yang ingin diedit
    Route::get('{id}/edit', [CartController::class, 'edit']);

    // Rute untuk update jumlah item di keranjang
    Route::post('{id}', [CartController::class, 'update'])->name('cart.update');

    // Rute untuk menghapus item dari keranjang
    Route::delete('{id}', [CartController::class, 'delete'])->name('cart.delete');

    // Menambahkan rute untuk menghitung jumlah item di keranjang
    Route::get('count', [CartController::class, 'cartCount'])->name('cart.count');
});

Route::post('/order', [OrderController::class, 'placeOrder'])->name('order.place');

Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{id}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::post('/checkout/delete/{id}', [CheckoutController::class, 'destroy'])->name('checkout.destroy');
Route::post('/checkout/get-snap-token', [CheckoutController::class, 'getSnapToken'])->name('checkout.getSnapToken');


Route::get('/test-midtrans', function () {
    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');

    $params = [
        'transaction_details' => [
            'order_id' => 'TEST-' . time(),
            'gross_amount' => 10000,
        ],
        'customer_details' => [
            'first_name' => 'Tester',
            'last_name' => 'Midtrans',
            'email' => 'test@example.com',
            'phone' => '081234567890',
        ],
    ];

    try {
        $snapToken = \Midtrans\Snap::getSnapToken($params);
        return response()->json(['snap_token' => $snapToken]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});


