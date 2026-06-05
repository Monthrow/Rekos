@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('profile.index') }}" class="text-slate-400 hover:text-slate-600">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <h2 class="text-xl font-bold text-slate-800">Edit Profil Anda</h2>
        </div>

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Nama / Username</label>
                <input type="text" name="username" value="{{ old('username', $user->username) }}" required class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none">
                @error('username') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">No. Telepon</label>
                <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none">
                @error('no_telp') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 mb-1">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none">{{ old('alamat', $user->alamat) }}</textarea>
                @error('alamat') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <hr class="border-slate-100 my-4">
            <p class="text-xs text-slate-400 font-medium italic">* Kosongkan password jika tidak ingin diganti.</p>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Password Baru</label>
                    <input type="password" name="password" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none">
                    @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full border border-slate-300 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-blue-400 outline-none">
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3.5 rounded-xl transition mt-6 shadow-lg shadow-blue-200">
                <i class="fas fa-save mr-1"></i> Simpan Perubahan
            </button>
        </form>
    </div>
</div>
@endsection