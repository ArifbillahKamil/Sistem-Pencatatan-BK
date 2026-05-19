@extends('layouts.app')

@section('title', 'Edit Siswa')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('siswa.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Edit Siswa — {{ $siswa->nama_siswa }}</h1>
        <p class="text-slate-500 text-sm">Perbarui data siswa</p>
    </div>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('siswa.update', $siswa->id_siswa) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                <div class="sm:col-span-2">
                    <label for="nama_siswa" class="block text-sm font-medium text-slate-700 mb-1.5">Nama Siswa <span class="text-red-500">*</span></label>
                    <input type="text" id="nama_siswa" name="nama_siswa" value="{{ old('nama_siswa', $siswa->nama_siswa) }}"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                                  {{ $errors->has('nama_siswa') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                    @error('nama_siswa') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="nisn" class="block text-sm font-medium text-slate-700 mb-1.5">NISN <span class="text-red-500">*</span></label>
                    <input type="text" id="nisn" name="nisn" value="{{ old('nisn', $siswa->nisn) }}" maxlength="10"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm font-mono focus:outline-none focus:ring-2 focus:ring-blue-400
                                  {{ $errors->has('nisn') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                    @error('nisn') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-slate-700 mb-1.5">Jenis Kelamin <span class="text-red-500">*</span></label>
                    <select id="jenis_kelamin" name="jenis_kelamin"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                                   {{ $errors->has('jenis_kelamin') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                        <option value="L" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'L' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="P" {{ old('jenis_kelamin', $siswa->jenis_kelamin) === 'P' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="id_kelas" class="block text-sm font-medium text-slate-700 mb-1.5">Kelas <span class="text-red-500">*</span></label>
                    <select id="id_kelas" name="id_kelas"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                                   {{ $errors->has('id_kelas') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                        @foreach($kelasList as $k)
                        <option value="{{ $k->id_kelas }}" {{ old('id_kelas', $siswa->id_kelas) == $k->id_kelas ? 'selected' : '' }}>
                            {{ $k->nama_kelas }} (Tingkat {{ $k->tingkat }})
                        </option>
                        @endforeach
                    </select>
                    @error('id_kelas') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="tanggal_lahir" class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Lahir <span class="text-red-500">*</span></label>
                    <input type="date" id="tanggal_lahir" name="tanggal_lahir"
                           value="{{ old('tanggal_lahir', $siswa->tanggal_lahir?->format('Y-m-d')) }}"
                           max="{{ date('Y-m-d', strtotime('-1 day')) }}"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                                  {{ $errors->has('tanggal_lahir') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                    @error('tanggal_lahir') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="no_telp" class="block text-sm font-medium text-slate-700 mb-1.5">No. Telepon</label>
                    <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp', $siswa->no_telp) }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                </div>

                <div class="sm:col-span-2">
                    <label for="alamat" class="block text-sm font-medium text-slate-700 mb-1.5">Alamat</label>
                    <textarea id="alamat" name="alamat" rows="3"
                              class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white resize-none">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                    Perbarui Siswa
                </button>
                <a href="{{ route('siswa.index') }}"
                   class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
