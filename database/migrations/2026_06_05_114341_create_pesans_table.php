<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pesans', function (Blueprint $table) {
            $table->id('id_pesan');
            $table->foreignId('id_pembeli')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_penjual')->constrained('users', 'id_user')->onDelete('cascade');
            $table->foreignId('id_barang')->nullable()->constrained('barangs', 'id_barang')->onDelete('set null');
            $table->integer('id_pengirim');
            $table->text('isi_pesan');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('pesans');
    }
};