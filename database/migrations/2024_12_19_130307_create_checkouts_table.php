<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checkouts', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique()->nullable(); // Kode pesanan unik
            $table->string('customer_name'); // Nama pelanggan
            $table->string('phone_number'); // Nomor telepon
            $table->integer('table_number'); // Nomor meja
            $table->integer('floor_number'); // Nomor lantai
            $table->enum('payment_method', ['cash', 'qris']); // Metode pembayaran
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid'); // Status pembayaran
            $table->decimal('subtotal', 10, 2)->default(0); // Menambahkan kolom subtotal
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkouts');
    }
};
