<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Penjual - Rekos Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex min-h-screen">

    <!-- Sidebar -->
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col p-6">
        <h2 class="text-xl font-black text-blue-500 tracking-tight mb-8">RE<span class="text-white">KOS ADMIN</span></h2>
        <nav class="space-y-2 text-sm font-semibold flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition">
                <i class="fas fa-chart-pie"></i> Statistik Utama
            </a>
            <a href="{{ route('admin.barang') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition">
                <i class="fas fa-boxes"></i> Daftar Barang
            </a>
            <a href="{{ route('admin.penjual') }}" class="flex items-center gap-3 px-4 py-3 text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition">
                <i class="fas fa-store"></i> Daftar Penjual
            </a>
            <a href="{{ route('admin.pendaftaran') }}" class="flex items-center justify-between px-4 py-3 bg-slate-800 text-white rounded-xl">
                <span class="flex items-center gap-3">
                    <i class="fas fa-user-plus"></i> Pendaftaran Penjual
                </span>
                @if(isset($total_pendaftar) && $total_pendaftar > 0)
                    <span class="bg-red-500 text-white text-xs font-black px-2 py-0.5 rounded-full">{{ $total_pendaftar }}</span>
                @endif
            </a>
        </nav>
        <a href="{{ route('logout') }}" class="flex items-center justify-center gap-2 px-4 py-3 bg-red-600/20 text-red-400 border border-red-500/20 hover:bg-red-600 hover:text-white transition font-bold text-xs rounded-xl">
            Keluar Sistem
        </a>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-8 overflow-y-auto">

        <!-- Back button -->
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition text-sm font-semibold mb-6">
            <i class="fas fa-arrow-left"></i> Kembali ke Dashboard
        </a>

        <h1 class="text-2xl font-black mb-2">Pendaftaran Penjual Baru</h1>
        <p class="text-slate-400 text-sm mb-8">Setujui atau tolak pendaftaran dalam 24 jam. Lewat dari itu otomatis ditolak.</p>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-900/50 text-emerald-400 rounded-2xl font-semibold flex items-center gap-2 border border-emerald-700">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden">
            <table class="w-full text-sm text-left">
                <thead>
                    <tr class="border-b border-slate-800 text-slate-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4">Nama</th>
                        <th class="px-6 py-4">Email</th>
                        <th class="px-6 py-4">No. HP</th>
                        <th class="px-6 py-4">Alamat</th>
                        <th class="px-6 py-4">Daftar Sejak</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($pendaftar as $user)
                        <tr class="hover:bg-slate-900 transition">
                            <td class="px-6 py-4 font-bold text-white">{{ $user->username }}</td>
                            <td class="px-6 py-4 text-slate-400">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $user->no_telp ?? '-' }}</td>
                            <td class="px-6 py-4 text-slate-300">{{ $user->alamat ?? '-' }}</td>
                            <td class="px-6 py-4">
                                <span class="text-slate-300">
                                    {{ $user->tgl_daftar_penjual ? \Carbon\Carbon::parse($user->tgl_daftar_penjual)->format('d M Y, H:i') : '-' }}
                                </span>
                                @if($user->tgl_daftar_penjual)
                                    @php $sisa = \Carbon\Carbon::parse($user->tgl_daftar_penjual)->addHours(24)->diffForHumans() @endphp
                                    <div class="text-xs text-amber-400 font-semibold mt-1">
                                        <i class="fas fa-clock mr-1"></i>Batas: {{ $sisa }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex gap-2 justify-center">
                                    <form action="{{ route('admin.approve') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                                            <i class="fas fa-check mr-1"></i> Setujui
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.tolak') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="id_user" value="{{ $user->id_user }}">
                                        <button type="submit" class="bg-red-600/30 hover:bg-red-600 text-red-400 hover:text-white text-xs font-bold px-4 py-2 rounded-xl transition border border-red-700">
                                            <i class="fas fa-times mr-1"></i> Tolak
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-12 text-slate-500">
                                <i class="fas fa-check-circle text-3xl block mb-3 text-slate-700"></i>
                                Tidak ada pendaftaran baru.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </main>
</body>
</html>