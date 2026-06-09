@extends('layouts.app')
@section('title', 'Pembayaran')
@section('content')

<div class="max-w-2xl mx-auto px-4 py-8">

    <!-- Header -->
    <div class="mb-6">
        <a href="{{ route('barang.detail', $barang->id_barang) }}" class="inline-flex items-center text-sm font-semibold text-blue-600 hover:text-blue-700 transition">
            <i class="fas fa-arrow-left mr-2"></i> Kembali
        </a>
        <h1 class="text-2xl font-bold text-slate-800 mt-3">Pembayaran</h1>
        <p class="text-sm text-slate-500 mt-1">Pilih metode pembayaran yang kamu inginkan.</p>
    </div>

    <!-- Ringkasan Pesanan -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
            <i class="fas fa-receipt text-blue-500"></i> Ringkasan Pesanan
        </h3>
        <div class="space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Barang</span>
                <span class="font-semibold text-slate-800">{{ $barang->nama_barang }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Jumlah</span>
                <span class="font-semibold text-slate-800">{{ $request->jumlah_beli }} unit</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-slate-500">Harga Satuan</span>
                <span class="font-semibold text-slate-800">Rp {{ number_format($barang->harga, 0, ',', '.') }}</span>
            </div>
            <div class="border-t border-slate-100 pt-3 flex justify-between">
                <span class="font-bold text-slate-700">Total Pembayaran</span>
                <span class="font-black text-blue-600 text-lg">Rp {{ number_format($total, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <!-- Pilihan Metode Pembayaran -->
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
            <i class="fas fa-wallet text-blue-500"></i> Metode Pembayaran
        </h3>
        <div class="grid grid-cols-2 gap-3">
            <!-- Pilihan QRIS -->
            <button type="button" onclick="pilihMetode('qris')"
                id="btn-qris"
                class="metode-btn border-2 border-blue-500 bg-blue-50 rounded-xl p-4 flex flex-col items-center gap-2 transition cursor-pointer">
                <i class="fas fa-qrcode text-2xl text-blue-500"></i>
                <span class="text-sm font-bold text-blue-700">QRIS</span>
                <span class="text-xs text-slate-500 text-center">Bayar sekarang, barang diantar</span>
            </button>
            <!-- Pilihan COD -->
            <button type="button" onclick="pilihMetode('cod')"
                id="btn-cod"
                class="metode-btn border-2 border-slate-200 bg-white rounded-xl p-4 flex flex-col items-center gap-2 transition cursor-pointer">
                <i class="fas fa-handshake text-2xl text-slate-400"></i>
                <span class="text-sm font-bold text-slate-600">COD</span>
                <span class="text-xs text-slate-500 text-center">Bayar saat ambil barang langsung</span>
            </button>
        </div>
    </div>

    <!-- QRIS Box (tampil jika pilih QRIS) -->
    <div id="box-qris" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <h3 class="text-sm font-bold text-slate-700 mb-1 flex items-center justify-center gap-2">
            <i class="fas fa-qrcode text-blue-500"></i> Scan QRIS untuk Membayar
        </h3>
        <p class="text-xs text-slate-400 mb-4 text-center">Gunakan aplikasi e-wallet atau mobile banking</p>
        <div class="flex flex-col items-center justify-center">
            <div class="p-3 bg-white border-2 border-slate-200 rounded-2xl shadow-inner mb-3">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=REKOS-{{ $barang->id_barang }}-{{ $total }}" 
                     alt="QRIS" class="w-44 h-44 rounded-lg">
            </div>
            <div class="bg-blue-50 rounded-xl px-4 py-2">
                <p class="text-xs text-blue-600 font-semibold">
                    Total: <span class="text-blue-700 font-black">Rp {{ number_format($total, 0, ',', '.') }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Alamat Pengiriman (tampil jika pilih QRIS) -->
    <div id="box-alamat" class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <h3 class="text-sm font-bold text-slate-700 mb-4 flex items-center gap-2">
            <i class="fas fa-map-marker-alt text-blue-500"></i> Alamat Pengiriman
        </h3>
        <div class="space-y-3">
            <div>
                <label class="text-xs text-slate-500 font-semibold mb-1 block">Nama Penerima</label>
                <input type="text" name="nama_penerima"
                       value="{{ auth()->user()->username }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                       placeholder="Nama lengkap penerima">
            </div>
            <div>
                <label class="text-xs text-slate-500 font-semibold mb-1 block">Alamat Lengkap</label>
                <textarea name="alamat_pengiriman" rows="3"
                          class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                          placeholder="Jalan, nomor rumah, RT/RW, kelurahan...">{{ auth()->user()->alamat }}</textarea>
            </div>
            <div>
                <label class="text-xs text-slate-500 font-semibold mb-1 block">Nomor HP</label>
                <input type="text" name="no_hp_penerima"
                       value="{{ auth()->user()->no_telp }}"
                       class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-300"
                       placeholder="08xxxxxxxxxx">
            </div>
        </div>
    </div>

    <!-- COD Box (tampil jika pilih COD) -->
    <div id="box-cod" class="hidden bg-white rounded-2xl border border-slate-100 shadow-sm p-5 mb-5">
        <h3 class="text-sm font-bold text-slate-700 mb-1 flex items-center justify-center gap-2">
            <i class="fas fa-handshake text-green-500"></i> Cash on Delivery
        </h3>
        <p class="text-xs text-slate-400 mb-4 text-center">Bayar langsung saat mengambil barang dari penjual</p>
        <div class="bg-green-50 rounded-xl p-4 text-center">
            <i class="fas fa-info-circle text-green-500 text-xl mb-2"></i>
            <p class="text-sm text-green-700 font-semibold">Hubungi penjual melalui chat</p>
            <p class="text-xs text-green-600 mt-1">untuk menentukan tempat dan waktu pengambilan barang.</p>
        </div>
    </div>

    <!-- Form Konfirmasi -->
    <form action="{{ route('transaksi.konfirmasi', $barang->id_barang) }}" method="POST">
        @csrf
        <input type="hidden" name="jumlah_beli" value="{{ $request->jumlah_beli }}">
        <input type="hidden" name="total_harga" value="{{ $total }}">
        <input type="hidden" name="metode_bayar" id="input-metode" value="qris">

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3.5 px-6 rounded-xl transition shadow-md hover:shadow-lg flex items-center justify-center gap-2 text-sm mb-3">
            <i class="fas fa-check-circle"></i> Konfirmasi
        </button>
        <a href="{{ route('barang.detail', $barang->id_barang) }}" 
           class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-3 px-6 rounded-xl transition flex items-center justify-center gap-2 text-sm">
            <i class="fas fa-times"></i> Batal
        </a>
    </form>

</div>

<script>
function pilihMetode(metode) {
    const btnQris   = document.getElementById('btn-qris');
    const btnCod    = document.getElementById('btn-cod');
    const boxQris   = document.getElementById('box-qris');
    const boxCod    = document.getElementById('box-cod');
    const boxAlamat = document.getElementById('box-alamat');
    const input     = document.getElementById('input-metode');

    if (metode === 'qris') {
        // Aktifkan tombol QRIS
        btnQris.classList.add('border-blue-500', 'bg-blue-50');
        btnQris.classList.remove('border-slate-200', 'bg-white');
        btnQris.querySelector('i').classList.replace('text-slate-400', 'text-blue-500');
        btnQris.querySelector('span').classList.replace('text-slate-600', 'text-blue-700');

        // Nonaktifkan tombol COD
        btnCod.classList.add('border-slate-200', 'bg-white');
        btnCod.classList.remove('border-green-500', 'bg-green-50');
        btnCod.querySelector('i').classList.replace('text-green-500', 'text-slate-400');
        btnCod.querySelector('span').classList.replace('text-green-700', 'text-slate-600');

        // Tampilkan QRIS & alamat, sembunyikan COD
        boxQris.classList.remove('hidden');
        boxAlamat.classList.remove('hidden');
        boxCod.classList.add('hidden');

    } else {
        // Aktifkan tombol COD
        btnCod.classList.add('border-green-500', 'bg-green-50');
        btnCod.classList.remove('border-slate-200', 'bg-white');
        btnCod.querySelector('i').classList.replace('text-slate-400', 'text-green-500');
        btnCod.querySelector('span').classList.replace('text-slate-600', 'text-green-700');

        // Nonaktifkan tombol QRIS
        btnQris.classList.add('border-slate-200', 'bg-white');
        btnQris.classList.remove('border-blue-500', 'bg-blue-50');
        btnQris.querySelector('i').classList.replace('text-blue-500', 'text-slate-400');
        btnQris.querySelector('span').classList.replace('text-blue-700', 'text-slate-600');

        // Tampilkan COD, sembunyikan QRIS & alamat
        boxCod.classList.remove('hidden');
        boxQris.classList.add('hidden');
        boxAlamat.classList.add('hidden');
    }

    input.value = metode;
}
</script>

@endsection