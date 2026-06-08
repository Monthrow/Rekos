<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    
    public function showLogin() {
        return view('auth.login');
    }

    public function login(Request $request) {
        // 1. Validasi input dari form login
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        if ($user) {
            // 3. Cek password menggunakan Hash::check (Standar Laravel)
            // Ini akan mencocokkan teks biasa dengan password terenkripsi Bcrypt di database
            if (Hash::check($request->password, $user->password)) {
                
                // Login-kan user ke dalam sistem
                Auth::login($user);
                $request->session()->regenerate();
                
                // 4. Pengalihan halaman (Redirection) berdasarkan Role
                if (strtolower($user->role) === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                
                return redirect()->route('dashboard');
            }
        }

        // Jika username tidak ketemu atau password salah, kembalikan dengan error
        return back()->with('error', 'Username atau Password salah!');
    }

    public function showRegister() {
        return view('auth.register');
    }

    public function register(Request $request) {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string',
        ]);

        $email = strtolower($request->email);

        // Validasi khusus email UPN Jatim
        if (!str_ends_with($email, '@student.upnjatim.ac.id')) {
            return back()->with('error', 'Registrasi gagal! Hanya email mahasiswa UPN Jatim (@student.upnjatim.ac.id) yang diizinkan.');
        }

        // Cek apakah email sudah terdaftar
        if (User::where('email', $email)->exists()) {
            return back()->with('error', 'Email sudah terdaftar! Silakan gunakan email lain.');
        }

        // Membuat user baru (Default role: pembeli)
        User::create([
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password), // Password di-hash dengan Bcrypt
            'role' => 'pembeli',
        ]);

        return redirect()->route('login')->with('success', 'Registrasi Berhasil! Silakan Login.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}