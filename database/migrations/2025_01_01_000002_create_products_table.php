<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang')->unique();
            $table->string('nama_barang');
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->unsignedInteger('stok')->default(0);
            $table->unsignedInteger('stok_minimum')->default(5);
            $table->string('lokasi_penyimpanan')->nullable();
            $table->enum('kondisi_barang', ['baik', 'rusak_ringan', 'rusak_berat'])->default('baik');
            $table->string('gambar')->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index(['nama_barang']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
