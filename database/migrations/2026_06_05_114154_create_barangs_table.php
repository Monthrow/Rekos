<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id('id_barang');
            $table->string('nama_barang', 150);
            $table->text('deskripsi')->nullable();
            $table->integer('harga');
            $table->integer('jumlah');
            $table->string('gambar')->nullable();
            $table->foreignId('id_user')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_pembeli')->nullable()->constrained('users', 'id_user')->onDelete('set null');
            $table->dateTime('waktu_beli')->nullable();
            $table->enum('status_barang', ['tersedia', 'pending', 'terjual'])->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('barangs');
    }
};