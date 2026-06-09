<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard Panel</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex min-h-screen">
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col p-6">
        <h2 class="text-xl font-black text-blue-500 tracking-tight mb-8">RE<span class="text-white">KOS ADMIN</span></h2>
        <nav class="space-y-2 text-sm font-semibold flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 bg-slate-800 text-white rounded-xl">
                <i class="fas fa-chart-pie"></i> Statistik Utama
            </a>
            <a href="{{ route('admin.barang') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition">
                <i class="fas fa-boxes"></i> Daftar Barang
            </a>
            <a href="{{ route('admin.penjual') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition">
                <i class="fas fa-store"></i> Daftar Penjual
            </a>
            <a href="{{ route('admin.pendaftaran') }}" class="flex items-center justify-between px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition">
                <span class="flex items-center gap-3">
                    <i class="fas fa-user-plus"></i> Pendaftaran Penjual
                </span>
                @if($total_pendaftar > 0)
                    <span class="bg-red-500 text-white text-xs font-black px-2 py-0.5 rounded-full">{{ $total_pendaftar }}</span>
                @endif
            </a>
        </nav>
        <a href="{{ route('logout') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-red-600/20 text-red-400 border border-red-500/20 hover:bg-red-600 hover:text-white transition font-bold text-xs rounded-xl">
            Keluar Sistem
        </a>
    </aside>

    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-2xl font-black mb-8">Statistik Kinerja Rekos</h1>

        <div class="grid grid-cols-2 md:grid-cols-5 gap-6 mb-8">
            <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Penjual</p>
                <p class="text-3xl font-black text-blue-500 mt-2">{{ $total_penjual }}</p>
            </div>
            <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Pembeli</p>
                <p class="text-3xl font-black text-emerald-500 mt-2">{{ $total_pembeli }}</p>
            </div>
            <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Barang</p>
                <p class="text-3xl font-black text-purple-500 mt-2">{{ $total_barang }}</p>
            </div>
            <div class="bg-slate-950 border border-slate-800 p-6 rounded-2xl">
                <p class="text-xs text-slate-400 font-bold uppercase">Total Transaksi</p>
                <p class="text-3xl font-black text-amber-500 mt-2">{{ $total_transaksi }}</p>
            </div>
            <a href="{{ route('admin.pendaftaran') }}" class="bg-red-950 border border-red-800 p-6 rounded-2xl hover:bg-red-900 transition">
                <p class="text-xs text-red-400 font-bold uppercase">Menunggu Approval</p>
                <p class="text-3xl font-black text-red-400 mt-2">{{ $total_pendaftar }}</p>
                <p class="text-xs text-red-500 mt-1">Pendaftar penjual baru</p>
            </a>
        </div>

        @if($total_pendaftar > 0)
        <div class="bg-red-950 border border-red-800 rounded-2xl p-4 mb-8 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class="fas fa-bell text-red-400 text-lg"></i>
                <div>
                    <p class="font-bold text-red-300 text-sm">Ada {{ $total_pendaftar }} pendaftaran penjual baru yang menunggu konfirmasi!</p>
                    <p class="text-xs text-red-500">Segera proses sebelum 24 jam habis.</p>
                </div>
            </div>
            <a href="{{ route('admin.pendaftaran') }}" class="bg-red-600 hover:bg-red-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                Proses Sekarang
            </a>
        </div>
        @endif

        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
            <h3 class="font-bold text-sm text-slate-300 mb-4">Postingan Produk Terbaru</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                @forelse($barang_terbaru as $b)
                    <div class="bg-slate-900 border border-slate-800 rounded-xl overflow-hidden text-xs">
                        <img src="{{ asset('assets/img/'.$b->gambar) }}" class="h-32 w-full object-cover" alt="">
                        <div class="p-3">
                            <p class="font-bold line-clamp-1 text-slate-200">{{ $b->nama_barang }}</p>
                            <p class="text-blue-400 font-extrabold mt-1">Rp {{ number_format($b->harga, 0, ',', '.') }}</p>
                            <p class="text-[10px] text-slate-500 mt-2"><i class="fas fa-user mr-1"></i> {{ $b->penjual->username }}</p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-slate-500 py-6">Belum ada barang terupload.</p>
                @endforelse
            </div>
        </div>
    </main>
</body>
</html>