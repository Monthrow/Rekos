<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\NotifikasiController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLogin'])->name('login');
    Route::get('/login', [AuthController::class, 'showLogin']);
    Route::post('/', [AuthController::class, 'login']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile/index', [BarangController::class, 'profil'])->name('profile.index');
    Route::post('/profile/switch-role', [ProfileController::class, 'switchRole'])->name('profile.switch-role');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/edit-profil', [ProfileController::class, 'edit'])->name('profil.edit');
    Route::post('/edit-profil', [ProfileController::class, 'update'])->name('profil.update');

    // Auth
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/notifikasi/baca-semua', [NotifikasiController::class, 'bacaSemua'])->name('notifikasi.baca-semua');
    Route::post('/notifikasi/baca/{id}', [NotifikasiController::class, 'baca'])->name('notifikasi.baca');

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Barang Umum
    Route::get('/detail-barang/{id}', [BarangController::class, 'detail'])->name('barang.detail');
    Route::post('/detail-barang/{id}/chat', [BarangController::class, 'kirimPesan'])->name('barang.chat');

    Route::get('/bayar/{id}', [BarangController::class, 'halamanBayar'])->name('barang.bayar');
    Route::post('/bayar/{id}', [BarangController::class, 'prosesBayar'])->name('barang.proses_bayar');

    Route::get('/jual-barang', [BarangController::class, 'halamanJual'])->name('barang.jual');
    Route::post('/jual-barang/daftar', [BarangController::class, 'daftarPenjual'])->name('barang.daftar_penjual');

    // Transaksi
    Route::get('/checkout/{id}', [TransaksiController::class, 'checkout'])->name('transaksi.checkout');
    Route::post('/proses-beli/{id}', [TransaksiController::class, 'prosesBeli'])->name('transaksi.proses');
    Route::post('/konfirmasi-bayar/{id}', [TransaksiController::class, 'konfirmasiBayar'])->name('transaksi.konfirmasi');

    // Khusus Role Penjual
    Route::middleware('role:penjual')->group(function () {
        Route::get('/barang-saya', [BarangController::class, 'barangSaya'])->name('barang.saya');
        Route::post('/jual-barang/upload', [BarangController::class, 'storeBarang'])->name('barang.store');
        Route::get('/edit-barang/{id}', [BarangController::class, 'editBarang'])->name('barang.edit');
        Route::post('/edit-barang/{id}/update', [BarangController::class, 'updateBarang'])->name('barang.update');
        Route::post('/hapus-barang', [BarangController::class, 'hapusBarang'])->name('barang.hapus');
        Route::get('/proses-konfirmasi/{id}', [BarangController::class, 'prosesKonfirmasi'])->name('barang.konfirmasi');

        Route::get('/report/penjual/pdf', [ReportController::class, 'penjualPdf'])->name('report.penjual.pdf');
        Route::get('/report/penjual/excel', [ReportController::class, 'penjualExcel'])->name('report.penjual.excel');
    });

    // Khusus Role Admin
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/barang', [AdminController::class, 'barang'])->name('admin.barang');
        Route::get('/penjual', [AdminController::class, 'penjual'])->name('admin.penjual');
        Route::post('/penjual/hapus', [AdminController::class, 'hapusPenjual'])->name('admin.penjual.hapus');
        Route::get('/pendaftaran-penjual', [AdminController::class, 'pendaftaranPenjual'])->name('admin.pendaftaran');
        Route::post('/pendaftaran-penjual/approve', [AdminController::class, 'approvePenjual'])->name('admin.approve');
        Route::post('/pendaftaran-penjual/tolak', [AdminController::class, 'tolakPenjual'])->name('admin.tolak');
        Route::get('/report/pdf', [ReportController::class, 'adminPdf'])->name('report.admin.pdf');
        Route::get('/report/excel', [ReportController::class, 'adminExcel'])->name('report.admin.excel');
    });

});