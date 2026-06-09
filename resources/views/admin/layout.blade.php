<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-[#060B19] text-slate-100 font-sans antialiased min-h-screen overflow-hidden">

    <div class="grid grid-cols-[16rem_1fr] h-screen w-screen overflow-hidden">
        
        <aside class="bg-[#0B132B] border-r border-slate-800/60 h-full flex flex-col justify-between overflow-hidden">
            
            <div class="flex-1 overflow-y-auto p-6 custom-scrollbar">
                <h2 class="text-xl font-black text-blue-500 tracking-tight mb-8">RE<span class="text-white">KOS ADMIN</span></h2>
                
                <nav class="space-y-2 text-sm font-semibold">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 @if(request()->routeIs('admin.dashboard')) bg-slate-800/60 text-white rounded-xl @else text-slate-400 hover:bg-slate-900/40 hover:text-white rounded-xl transition @endif">
                        <i class="fas fa-chart-pie w-5 text-center"></i> Statistik Utama
                    </a>
                    <a href="{{ route('admin.barang') }}" class="flex items-center gap-3 px-4 py-3 @if(request()->routeIs('admin.barang')) bg-slate-800/60 text-white rounded-xl @else text-slate-400 hover:bg-slate-900/40 hover:text-white rounded-xl transition @endif">
                        <i class="fas fa-boxes w-5 text-center"></i> Daftar Barang
                    </a>
                    <a href="{{ route('admin.penjual') }}" class="flex items-center gap-3 px-4 py-3 @if(request()->routeIs('admin.penjual')) bg-slate-800/60 text-white rounded-xl @else text-slate-400 hover:bg-slate-900/40 hover:text-white rounded-xl transition @endif">
                        <i class="fas fa-store w-5 text-center"></i> Daftar Penjual
                    </a>
                    <a href="{{ route('admin.pendaftaran') }}" class="flex items-center justify-between px-4 py-3 @if(request()->routeIs('admin.pendaftaran')) bg-slate-800/60 text-white rounded-xl @else text-slate-400 hover:bg-slate-900/40 hover:text-white rounded-xl transition @endif">
                        <span class="flex items-center gap-3">
                            <i class="fas fa-user-plus w-5 text-center"></i> Pendaftaran Penjual
                        </span>
                        @if(isset($total_pendaftar) && $total_pendaftar > 0)
                            <span class="bg-red-500 text-white text-xs font-black px-2 py-0.5 rounded-full shadow-sm shadow-red-500/50">{{ $total_pendaftar }}</span>
                        @endif
                    </a>
                </nav>
            </div>

            {{-- PERBAIKAN DI SINI: Menggunakan tag <a> karena route logout bertipe GET --}}
            <div class="p-6 bg-[#0B132B] border-t border-slate-800/60 shadow-[0_-8px_20px_rgba(0,0,0,0.3)]">
                <a href="{{ route('logout') }}" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-red-950/40 text-red-400 border border-red-900/30 hover:bg-red-900/40 hover:text-red-300 transition font-bold text-xs rounded-xl block text-center">
                    <i class="fas fa-sign-out-alt"></i> Keluar Sistem
                </a>
            </div>
        </aside>
    
        <main class="h-full overflow-y-auto bg-[#060B19] p-8">
            <div class="max-w-7xl mx-auto">
                @yield('content')
            </div>
        </main>

    </div>

</body>
</html>