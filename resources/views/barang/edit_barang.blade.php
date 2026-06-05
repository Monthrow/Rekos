@extends('layouts.app')
@section('title', 'Edit Barang')
@section('content')
<div class="max-w-xl mx-auto bg-white border border-slate-200 rounded-3xl p-8 shadow-sm">
    <h1 class="text-xl font-bold text-slate-800 mb-6"><i class="fas fa-edit text-blue-600 mr-1"></i> Edit Data Produk</h1>
    <form action="{{ route('barang.update', $barang->id_barang) }}" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs font-semibold">
        @csrf
        <div>
            <label class="block text-slate-600 mb-1.5">Nama Barang</label>
            <input type="text" name="nama_barang" required value="{{ $barang->nama_barang }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-slate-600 mb-1.5">Harga (Rp)</label>
                <input type="number" name="harga" required value="{{ $barang->harga }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
            </div>
            <div>
                <label class="block text-slate-600 mb-1.5">Stok</label>
                <input type="number" name="jumlah" required value="{{ $barang->jumlah }}" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">
            </div>
        </div>
        <div>
            <label class="block text-slate-600 mb-1.5">Deskripsi</label>
            <textarea name="deskripsi" required rows="4" class="w-full px-4 py-2.5 border border-slate-300 rounded-xl outline-none focus:border-blue-500 font-medium">{{ $barang->deskripsi }}</textarea>
        </div>
        <div>
            <label class="block text-slate-600 mb-1.5">Ganti Foto Produk (Opsional)</label>
            <input type="file" name="gambar" accept="image/*" class="w-full border border-slate-300 rounded-xl px-4 py-2 file:bg-blue-600 file:text-white file:border-0 file:rounded-lg file:px-3 file:py-1 file:mr-3 cursor-pointer">
        </div>
        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-md mt-2">Simpan Perubahan</button>
    </form>
</div>
@endsection