<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun - Rekos</title>
    {{-- Memuat build assets Laravel Vite untuk CSS dan JavaScript --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-slate-900 to-blue-950 flex items-center justify-center min-h-screen p-4">
    <div class="w-full max-w-md bg-white/10 backdrop-blur-md rounded-3xl p-8 border border-white/20 shadow-2xl">
        <h2 class="text-3xl font-black text-white text-center tracking-tight mb-2">RE<span class="text-blue-400">KOS</span></h2>
        <p class="text-white/60 text-sm text-center mb-8">Buat akun menggunakan Email Student UPN</p>

        {{-- Menampilkan Pesan Error dari Sisi Server (Validasi Controller) --}}
        @if(session('error'))
            <div class="mb-4 p-3 bg-red-500/20 text-red-200 border border-red-500/30 rounded-xl text-sm font-medium">
                {{ session('error') }}
            </div>
        @endif

        {{-- Menampilkan Pesan Error Validasi Bawaan Laravel jika Input Kosong/Format Salah --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-500/20 text-red-200 border border-red-500/30 rounded-xl text-sm font-medium">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Form Mengirimkan ke Route POST 'register' --}}
        <form action="{{ route('register') }}" method="POST" onsubmit="return validateForm()" class="space-y-5">
            @csrf {{-- Token Keamanan Wajib Laravel untuk Method POST --}}
            
            <div>
                <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Nama Lengkap</label>
                <input type="text" id="regName" name="username" value="{{ old('username') }}" placeholder="Contoh: Ahmad Fauzi" required class="w-full border-0 border-b border-white/30 bg-transparent px-0 py-2.5 text-white placeholder:text-white/40 focus:outline-none focus:border-blue-400 font-medium transition">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Email Student UPN Jatim</label>
                <input type="email" id="regEmail" name="email" value="{{ old('email') }}" placeholder="username@student.upnjatim.ac.id" required class="w-full border-0 border-b border-white/30 bg-transparent px-0 py-2.5 text-white placeholder:text-white/40 focus:outline-none focus:border-blue-400 font-medium transition">
            </div>
            
            <div>
                <label class="block text-xs font-semibold text-white/80 uppercase tracking-wider mb-2">Password</label>
                <input type="password" id="regPassword" name="password" placeholder="Kombinasi huruf & angka" required class="w-full border-0 border-b border-white/30 bg-transparent px-0 py-2.5 text-white placeholder:text-white/40 focus:outline-none focus:border-blue-400 font-medium transition">
            </div>
            
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl transition shadow-lg shadow-blue-500/20 mt-4">
                Daftar Akun
            </button>
        </form>
        
        <p class="mt-6 text-sm text-white/70 text-center">Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-400 hover:underline font-semibold">Login di sini</a></p>
    </div>

    {{-- Validasi Sisi Klien Menggunakan JavaScript --}}
    <script>
    function validateForm() {
        const nameInput = document.getElementById('regName').value.trim();
        const emailInput = document.getElementById('regEmail').value.trim().toLowerCase();
        const passwordInput = document.getElementById('regPassword').value;

        // 1. Validasi Karakter Nama (Hanya Huruf dan Spasi)
        const nameRegex = /^[a-zA-Z\s]+$/;
        if (!nameRegex.test(nameInput)) {
            alert("Nama hanya bisa berisi huruf dan spasi!");
            return false;
        }
        
        // 2. Validasi Akhiran Domain Email Student UPN Jatim
        if (!emailInput.endsWith('@student.upnjatim.ac.id')) {
            alert("Harap gunakan email mahasiswa UPN Jatim (@student.upnjatim.ac.id)!");
            return false;
        }
        
        // 3. Validasi Kekuatan Password (Minimal 8 Karakter, Kombinasi Huruf & Angka)
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;
        if (!passwordRegex.test(passwordInput)) {
            alert("Password minimal 8 karakter dan harus mengandung kombinasi huruf dan angka!");
            return false;
        }
        
        return true;
    }
    </script>
</body>
</html>