<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-900 text-slate-100 font-sans antialiased flex min-h-screen">

    {{-- Sidebar --}}
    <aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col p-6">
        <h2 class="text-xl font-black text-blue-500 tracking-tight mb-8">RE<span class="text-white">KOS ADMIN</span></h2>
        <nav class="space-y-2 text-sm font-semibold flex-1">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 @if(request()->routeIs('admin.dashboard')) bg-slate-800 text-white rounded-xl @else text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition @endif">
                <i class="fas fa-chart-pie"></i> Statistik Utama
            </a>
            <a href="{{ route('admin.barang') }}" class="flex items-center gap-3 px-4 py-3 @if(request()->routeIs('admin.barang')) bg-slate-800 text-white rounded-xl @else text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition @endif">
                <i class="fas fa-boxes"></i> Daftar Barang
            </a>
            <a href="{{ route('admin.penjual') }}" class="flex items-center gap-3 px-4 py-3 @if(request()->routeIs('admin.penjual')) bg-slate-800 text-white rounded-xl @else text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition @endif">
                <i class="fas fa-store"></i> Daftar Penjual
            </a>
            <a href="{{ route('admin.pendaftaran') }}" class="flex items-center justify-between px-4 py-3 @if(request()->routeIs('admin.pendaftaran')) bg-slate-800 text-white rounded-xl @else text-slate-400 hover:bg-slate-900 hover:text-white rounded-xl transition @endif">
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

    {{-- Main Content --}}
    <main class="flex-1 p-8 overflow-y-auto">
        @yield('content')
    </main>
</body>
</html>