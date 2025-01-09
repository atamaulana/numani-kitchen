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
        Schema::create('checkout_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checkout_id')->constrained('checkouts')->onDelete('cascade'); // Relasi ke checkouts
            $table->string('menu_name'); // Nama menu
            $table->string('menu_image')->nullable(); // Gambar menu
            $table->decimal('menu_price', 10, 2); // Harga menu
            $table->integer('quantity'); // Jumlah
            $table->decimal('total_price', 10, 2); // Total harga item
            $table->string('level_pedas')->nullable(); // Level pedas
            $table->string('panas_dingin')->nullable(); // Panas atau dingin
            $table->string('level_es')->nullable(); // Level es batu
            $table->string('manis')->nullable(); // Level manis
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checkout_items');
    }
};
