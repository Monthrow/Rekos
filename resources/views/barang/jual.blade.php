@extends('layouts.app')
@section('title', 'Jual Barang Baru')
@section('content')
@if(strtolower($user->role) !== 'penjual')
<div class="max-w-md mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
    <div class="text-center mb-6">
        <i class="fas fa-store text-4xl text-blue-600 mb-3"></i>
        <h1 class="text-xl font-bold text-slate-800">Aktifkan Fitur Penjual</h1>
        <p class="text-xs text-slate-400 mt-1">Lengkapi data kos Anda untuk mulai memposting barang.</p>
    </div>
    <form action="{{ route('barang.daftar_penjual') }}" method="POST" class="space-y-4 text-xs font-semibold">
        @csrf
        <div>
            <label class="block text-slate-600 mb-1.5">Nomor WhatsApp Aktif</label>
            <input type="text" name="no_telp" required placeholder="Contoh: 08123456789" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
        </div>
        <div>
            <label class="block text-slate-600 mb-1.5">Alamat Kos Lengkap / Area COD</label>
            <textarea name="alamat" required rows="3" placeholder="Contoh: Kos Putri Melati, Jl. Teknik Kimia No. 12" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium"></textarea>
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-bold transition mt-2">Daftar Penjual</button>
    </form>
</div>
@else
<div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
    <h1 class="text-xl font-bold text-slate-800 mb-6 flex items-center gap-2"><i class="fas fa-plus-circle text-blue-600"></i> Upload Produk Baru</h1>
    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold">
        @csrf
        <div>
            <label class="block text-slate-600 mb-1.5">Nama Barang</label>
            <input type="text" name="nama_barang" required placeholder="Contoh: Kipas Angin Cosmos Dinding" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-slate-600 mb-1.5">Harga Jual (Rp)</label>
                <input type="number" name="harga" required placeholder="Harga net" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
            </div>
            <div>
                <label class="block text-slate-600 mb-1.5">Jumlah Stok</label>
                <input type="number" name="jumlah" required value="1" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
            </div>
        </div>
        <div>
            <label class="block text-slate-600 mb-1.5">Deskripsi Kondisi Barang</label>
            <textarea name="deskripsi" required rows="4" placeholder="Jelaskan kondisi minus pemakaian, lama pemakaian, dll..." class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium"></textarea>
        </div>
        <div>
            <label class="block text-slate-600 mb-1.5">Foto Produk</label>
            <input type="file" name="gambar" accept="image/*" required class="w-full border border-slate-300 rounded-xl px-4 py-2 file:bg-blue-600 file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1 file:mr-3 cursor-pointer">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-md mt-2">Publish Produk</button>
    </form>
</div>
@endif
@endsection