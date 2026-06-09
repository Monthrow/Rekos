<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Rekos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-50 text-slate-700 font-sans antialiased">
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50 shadow-sm">
        <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="{{ route('dashboard') }}" class="text-xl font-black text-blue-600 tracking-tight">RE<span class="text-slate-800">KOS</span></a>
            <div class="flex items-center gap-4">
                <span class="text-sm font-medium">Halo, <strong class="text-slate-900">{{ Auth::user()->username }}</strong></span>
                <a href="{{ route('profile.index') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition"><i class="fas fa-user-edit mr-1"></i>Profil</a>
                @if(Auth::user()->role === 'penjual')
                    <a href="{{ route('barang.jual') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition"><i class="fas fa-box mr-1"></i>Jual</a>
                    <a href="{{ route('barang.saya') }}" class="text-sm font-semibold text-blue-600 hover:underline"><i class="fas fa-store mr-1"></i>Barang Saya</a>
                @endif

                <!-- BELL NOTIFIKASI -->
                @php
                    $notifikasis = \App\Models\Notifikasi::where('id_user', Auth::id())
                        ->orderBy('created_at', 'desc')
                        ->take(10)
                        ->get();
                    $belumDibaca = $notifikasis->where('dibaca', false)->count();
                @endphp

                <div class="relative" id="notif-wrapper">
                    <button onclick="toggleNotif()" class="relative w-9 h-9 flex items-center justify-center rounded-xl hover:bg-slate-100 transition text-slate-600">
                        <i class="fas fa-bell text-base"></i>
                        @if($belumDibaca > 0)
                            <span class="absolute -top-0.5 -right-0.5 w-4 h-4 bg-red-500 text-white text-[9px] font-bold rounded-full flex items-center justify-center">
                                {{ $belumDibaca > 9 ? '9+' : $belumDibaca }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown Notifikasi -->
                    <div id="notif-dropdown" class="hidden absolute right-0 top-12 w-80 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 overflow-hidden">
                        <!-- Header -->
                        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                            <span class="text-sm font-bold text-slate-800">Notifikasi</span>
                            @if($belumDibaca > 0)
                                <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="text-xs text-blue-600 font-semibold hover:underline">Tandai semua dibaca</button>
                                </form>
                            @endif
                        </div>

                        <!-- List Notifikasi -->
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                            @forelse($notifikasis as $notif)
                                <div class="flex items-start gap-3 px-4 py-3 {{ $notif->dibaca ? 'bg-white' : 'bg-blue-50/50' }} hover:bg-slate-50 transition">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 mt-0.5
                                        {{ $notif->warna === 'green' ? 'bg-green-100 text-green-600' : 
                                           ($notif->warna === 'red' ? 'bg-red-100 text-red-600' : 
                                           ($notif->warna === 'amber' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600')) }}">
                                        <i class="{{ $notif->icon }} text-xs"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800">{{ $notif->judul }}</p>
                                        <p class="text-xs text-slate-500 mt-0.5 leading-relaxed">{{ $notif->pesan }}</p>
                                        <p class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </div>
                                    @if(!$notif->dibaca)
                                        <span class="w-2 h-2 bg-blue-500 rounded-full flex-shrink-0 mt-1.5"></span>
                                    @endif
                                </div>
                            @empty
                                <div class="py-10 text-center">
                                    <i class="fas fa-bell-slash text-2xl text-slate-300 mb-2 block"></i>
                                    <p class="text-xs text-slate-400">Belum ada notifikasi</p>
                                </div>
                            @endforelse
                        </div>

                        @if($notifikasis->isNotEmpty())
                            <div class="px-4 py-2 border-t border-slate-100">
                                <form action="{{ route('notifikasi.baca-semua') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full text-xs text-center text-slate-400 hover:text-blue-600 transition py-1">
                                        Tandai semua sudah dibaca
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <a href="{{ route('logout') }}" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-xl text-xs font-bold hover:bg-red-100 transition">Logout</a>
            </div>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>

    <script>
        function toggleNotif() {
            const dropdown = document.getElementById('notif-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Tutup dropdown kalau klik di luar
        document.addEventListener('click', function(e) {
            const wrapper = document.getElementById('notif-wrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                document.getElementById('notif-dropdown').classList.add('hidden');
            }
        });
    </script>
</body>
</html>