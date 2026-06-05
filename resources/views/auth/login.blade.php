<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Rekos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-900 to-emerald-950 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-2xl">
        <h2 class="text-3xl font-black text-white text-center tracking-tight mb-2">RE<span class="text-emerald-400">KOS</span></h2>
        <p class="text-white/60 text-sm text-center mb-8">Masuk ke platform jual-beli kos mahasiswa</p>
        
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-500/20 text-red-200 border border-red-500/30 rounded-xl text-sm font-medium">{{ session('error') }}</div>
        @endif
        @if(session('success'))
            <div class="mb-4 p-3 bg-emerald-500/20 text-emerald-200 border border-emerald-500/30 rounded-xl text-sm font-medium">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Username</label>
                <input type="text" name="username" placeholder="Masukkan username" required class="w-full border-0 border-b border-white/30 bg-transparent px-0 py-2.5 text-white placeholder:text-white/40 focus:outline-none focus:border-emerald-400 font-medium transition">
            </div>
            <div>
                <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required class="w-full border-0 border-b border-white/30 bg-transparent px-0 py-2.5 text-white placeholder:text-white/40 focus:outline-none focus:border-emerald-400 font-medium transition">
            </div>
            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-emerald-500/20 mt-4">Masuk Sekarang</button>
        </form>
        <p class="mt-6 text-sm text-white/70 text-center">Belum punya akun? <a href="{{ route('register') }}" class="text-emerald-400 hover:underline font-semibold">Daftar di sini</a></p>
    </div>
</body>
</html>