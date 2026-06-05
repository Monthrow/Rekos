<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Barang - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex min-h-screen">
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col p-6">
        <h2 class="text-xl font-black text-blue-500 tracking-tight mb-8">RE<span class="text-white">KOS ADMIN</span></h2>
        <nav class="space-y-2 text-sm font-semibold flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition"><i class="fas fa-chart-pie"></i> Statistik Utama</a>
            <a href="{{ route('admin.barang') }}" class="flex items-center gap-3 px-4 py-3 bg-slate-800 text-white rounded-xl"><i class="fas fa-boxes"></i> Daftar Barang</a>
            <a href="{{ route('admin.penjual') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition"><i class="fas fa-store"></i> Daftar Penjual</a>
        </nav>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">
        <div class="flex items-center justify-between mb-8">
            <h1 class="text-2xl font-black">Database Barang Pengguna</h1>
            <form action="{{ route('admin.barang') }}" method="GET" class="flex gap-2">
                <input type="text" name="q" value="{{ $keyword }}" placeholder="Cari nama barang..." class="px-4 py-2 bg-slate-950 border border-slate-800 rounded-xl focus:outline-none focus:border-blue-500 text-xs w-64">
                <button type="submit" class="px-4 py-2 bg-blue-600 font-bold text-xs rounded-xl hover:bg-blue-700 transition">Filter</button>
            </form>
        </div>
        <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden text-xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-slate-400 border-b border-slate-800">
                        <th class="p-4">Foto</th>
                        <th class="p-4">Nama Produk</th>
                        <th class="p-4">Penjual</th>
                        <th class="p-4">Harga</th>
                        <th class="p-4">Stok</th>
                        <th class="p-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($barang as $b)
                    <tr class="hover:bg-slate-900/50">
                        <td class="p-4"><img src="{{ asset('assets/img/'.$b->gambar) }}" class="w-12 h-12 object-cover rounded-lg" alt=""></td>
                        <td class="p-4 font-bold text-slate-200">{{ $b->nama_barang }}</td>
                        <td class="p-4"><span class="px-2 py-0.5 bg-slate-800 border border-slate-700 text-slate-300 rounded-md">{{ $b->penjual->username }}</span></td>
                        <td class="p-4 font-extrabold text-blue-400">Rp {{ number_format($b->harga, 0, ',', '.') }}</td>
                        <td class="p-4 text-slate-400">{{ $b->jumlah }} unit</td>
                        <td class="p-4 uppercase tracking-wider font-bold text-[10px] {{ $b->status_barang == 'terjual' ? 'text-red-400' : 'text-emerald-400' }}">{{ $b->status_barang }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-8 text-center text-slate-500">Data produk masih kosong.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>