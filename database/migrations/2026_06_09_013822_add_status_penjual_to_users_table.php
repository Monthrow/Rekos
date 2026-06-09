<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('status_penjual', ['menunggu', 'disetujui', 'ditolak'])->nullable()->after('role');
            $table->timestamp('tgl_daftar_penjual')->nullable()->after('status_penjual');
        });
    }

    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status_penjual', 'tgl_daftar_penjual']);
        });
    }
};