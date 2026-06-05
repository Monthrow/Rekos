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
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user) {
            $password_check = false;
            if (strtolower($user->role) === 'admin') {
                $password_check = (md5($request->password) === $user->password);
            } else {
                $password_check = Hash::check($request->password, $user->password);
            }

            if ($password_check) {
                Auth::login($user);
                $request->session()->regenerate();
                
                if (strtolower($user->role) === 'admin') {
                    return redirect()->route('admin.dashboard');
                }
                return redirect()->route('dashboard');
            }
        }

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

        if (!str_ends_with($email, '@student.upnjatim.ac.id')) {
            return back()->with('error', 'Registrasi gagal! Hanya email mahasiswa UPN Jatim (@student.upnjatim.ac.id) yang diizinkan.');
        }

        if (User::where('email', $email)->exists()) {
            return back()->with('error', 'Email sudah terdaftar! Silakan gunakan email lain.');
        }

        User::create([
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password),
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