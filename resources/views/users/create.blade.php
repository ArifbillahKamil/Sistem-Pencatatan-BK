@extends('layouts.app')

@section('title', 'Tambah User')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('users.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Tambah User</h1>
        <p class="text-slate-500 text-sm">Buat akun baru untuk Guru BK atau Wali Kelas</p>
    </div>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="nama_lengkap" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Nama Lengkap <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_lengkap" name="nama_lengkap"
                       value="{{ old('nama_lengkap') }}" placeholder="Nama lengkap beserta gelar"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                              {{ $errors->has('nama_lengkap') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                @error('nama_lengkap') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="username" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" id="username" name="username"
                       value="{{ old('username') }}" placeholder="Username untuk login"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-400
                              {{ $errors->has('username') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                @error('username') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Role <span class="text-red-500">*</span>
                </label>
                <select id="role" name="role"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                               {{ $errors->has('role') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                    <option value="">-- Pilih Role --</option>
                    <option value="guru_bk" {{ old('role') === 'guru_bk' ? 'selected' : '' }}>Guru BK</option>
                    <option value="wali_kelas" {{ old('role') === 'wali_kelas' ? 'selected' : '' }}>Wali Kelas</option>
                    <option value="guru_wali" {{ old('role') === 'guru_wali' ? 'selected' : '' }}>Guru Wali</option>
                </select>
                @error('role') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password" name="password" placeholder="Minimal 6 karakter"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                              {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                @error('password') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Konfirmasi Password <span class="text-red-500">*</span>
                </label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       placeholder="Ulangi password"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 border-slate-300 bg-white">
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                    Simpan User
                </button>
                <a href="{{ route('users.index') }}"
                   class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
