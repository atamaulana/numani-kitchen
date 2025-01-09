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
        // database/migrations/create_menu-items_table.php
        Schema::create('menu-items', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama menu
            $table->unsignedBigInteger('category_id'); // Relasi ke kategori
            $table->decimal('price', 10, 2); // Harga menu
            $table->integer('stock'); // Stok menu
            $table->string('image'); // Gambar menu
            $table->text('description')->nullable(); // Deskripsi menu
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu-items');
    }
};
