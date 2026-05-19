@extends('layouts.app')

@section('title', 'Edit Kelas')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('kelas.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Edit Kelas — {{ $kela->nama_kelas }}</h1>
        <p class="text-slate-500 text-sm">Perbarui data kelas</p>
    </div>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('kelas.update', $kela->id_kelas) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            {{-- Nama Kelas --}}
            <div>
                <label for="nama_kelas" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Nama Kelas <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_kelas" name="nama_kelas"
                       value="{{ old('nama_kelas', $kela->nama_kelas) }}"
                       placeholder="Contoh: 9A, 8B, 7C"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                              {{ $errors->has('nama_kelas') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                @error('nama_kelas')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tingkat --}}
            <div>
                <label for="tingkat" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Tingkat <span class="text-red-500">*</span>
                </label>
                <select id="tingkat" name="tingkat"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                               {{ $errors->has('tingkat') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                    @foreach([7, 8, 9] as $t)
                    <option value="{{ $t }}" {{ old('tingkat', $kela->tingkat) == $t ? 'selected' : '' }}>
                        Kelas {{ $t }} (SMP)
                    </option>
                    @endforeach
                </select>
                @error('tingkat')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Wali Kelas --}}
            <div>
                <label for="id_user" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Wali Kelas <span class="text-red-500">*</span>
                </label>
                <select id="id_user" name="id_user"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                               {{ $errors->has('id_user') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                    @foreach($waliKelasUsers as $u)
                    <option value="{{ $u->id }}" {{ old('id_user', $kela->id_user) == $u->id ? 'selected' : '' }}>
                        {{ $u->nama_lengkap }}
                    </option>
                    @endforeach
                </select>
                @error('id_user')
                    <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                    Perbarui Kelas
                </button>
                <a href="{{ route('kelas.index') }}"
                   class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
