<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lapangans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 100);
            $table->string('jenis_lapangan', 50); // Contoh: Futsal, Basket
            $table->text('deskripsi')->nullable();
            $table->string('image')->nullable(); // Tambahkan ini
            $table->integer('kapasitas')->nullable(); // Tambahkan ini
            $table->string('ukuran')->nullable(); // Tambahkan ini
            $table->unsignedBigInteger('harga_per_jam'); // Harga dalam Rupiah
            $table->enum('status', ['tersedia', 'tidak tersedia'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lapangans');
    }
};