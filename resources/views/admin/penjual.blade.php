<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Penjual - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex min-h-screen">
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col p-6">
        <h2 class="text-xl font-black text-blue-500 tracking-tight mb-8">RE<span class="text-white">KOS ADMIN</span></h2>
        <nav class="space-y-2 text-sm font-semibold flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition"><i class="fas fa-chart-pie"></i> Statistik Utama</a>
            <a href="{{ route('admin.barang') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition"><i class="fas fa-boxes"></i> Daftar Barang</a>
            <a href="{{ route('admin.penjual') }}" class="flex items-center gap-3 px-4 py-3 bg-slate-800 text-white rounded-xl"><i class="fas fa-store"></i> Daftar Penjual</a>
        </nav>
    </aside>
    <main class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-2xl font-black mb-8">Database Akun Merchant / Penjual</h1>
        
        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-500/20 text-emerald-200 border border-emerald-500/30 rounded-xl text-xs font-semibold">{{ session('success') }}</div>
        @endif

        <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden text-xs">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-900 text-slate-400 border-b border-slate-800">
                        <th class="p-4">Username</th>
                        <th class="p-4">Email Kampus</th>
                        <th class="p-4">No. Telepon</th>
                        <th class="p-4">Alamat Kos / Rumah</th>
                        <th class="p-4">Jumlah Produk</th>
                        <th class="p-4 text-center">Aksi Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-850">
                    @forelse($penjual as $p)
                    <tr class="hover:bg-slate-900/50">
                        <td class="p-4 font-bold text-slate-200">{{ $p->username }}</td>
                        <td class="p-4 text-slate-400">{{ $p->email }}</td>
                        <td class="p-4 text-slate-300">{{ $p->no_telp }}</td>
                        <td class="p-4 text-slate-400 max-w-xs truncate">{{ $p->alamat }}</td>
                        <td class="p-4 text-center font-bold text-blue-400">{{ $p->barangs_count }} unit</td>
                        <td class="p-4">
                            <div class="flex gap-2 justify-center">
                                <a href="{{ route('admin.barang', ['id_user' => $p->id_user]) }}" class="px-3 py-1.5 bg-blue-600/20 text-blue-400 border border-blue-500/20 hover:bg-blue-600 hover:text-white transition rounded-lg font-bold text-[11px]">Lihat Barang</a>
                                <form action="{{ route('admin.penjual.hapus') }}" method="POST" onsubmit="return confirm('Hapus penjual beserta seluruh barang miliknya?')">
                                    @csrf
                                    <input type="hidden" name="id_user" value="{{ $p->id_user }}">
                                    <button type="submit" class="px-3 py-1.5 bg-red-600/20 text-red-400 border border-red-500/20 hover:bg-red-600 hover:text-white transition rounded-lg font-bold text-[11px]">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="p-8 text-center text-slate-500">Belum ada penjual terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>