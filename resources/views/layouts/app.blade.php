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
            <div class="flex items-center gap-6">
                <span class="text-sm font-medium">Halo, <strong class="text-slate-900">{{ Auth::user()->username }}</strong></span>
                <a href="{{ route('profile.index') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition"><i class="fas fa-user-edit mr-1"></i>Profil</a>
                @if(Auth::user()->role === 'penjual')
                    <a href="{{ route('barang.jual') }}" class="text-sm font-semibold text-slate-600 hover:text-blue-600 transition"><i class="fas fa-box mr-1"></i>Jual</a>
                    <a href="{{ route('barang.saya') }}" class="text-sm font-semibold text-blue-600 hover:underline"><i class="fas fa-store mr-1"></i>Barang Saya</a>
                @endif
                <a href="{{ route('logout') }}" class="px-3 py-1.5 bg-red-50 text-red-600 rounded-xl text-xs font-bold hover:bg-red-100 transition">Logout</a>
            </div>
        </div>
    </nav>
    <main class="max-w-6xl mx-auto px-4 py-8">
        @yield('content')
    </main>
</body>
</html>