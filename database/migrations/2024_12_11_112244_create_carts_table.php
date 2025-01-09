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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->string('session_id'); // Menggunakan session ID untuk identifikasi keranjang
            $table->unsignedBigInteger('menu_items_id'); // Relasi ke tabel menu_items
            $table->integer('quantity')->default(1); // Jumlah item
            $table->string('level_pedas')->nullable(); // Level pedas
            $table->string('panas_dingin')->nullable(); // Panas atau dingin
            $table->string('level_es')->nullable(); // Level es batu
            $table->string('manis')->nullable(); // Level manis
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('menu_items_id')->references('id')->on('menu_items')->onDelete('cascade');
            $table->foreignId('checkout_id')->nullable()->constrained()->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
