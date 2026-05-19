@extends('layouts.app')

@section('title', 'Edit Jenis Pelanggaran')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('jenis-pelanggaran.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Edit Jenis Pelanggaran</h1>
        <p class="text-slate-500 text-sm">{{ $jenisPelanggaran->nama_pelanggaran }}</p>
    </div>
</div>

<div class="max-w-xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('jenis-pelanggaran.update', $jenisPelanggaran->id_jenis) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            {{-- Nama Pelanggaran --}}
            <div>
                <label for="nama_pelanggaran" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Nama Pelanggaran <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_pelanggaran" name="nama_pelanggaran"
                       value="{{ old('nama_pelanggaran', $jenisPelanggaran->nama_pelanggaran) }}"
                       class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                              {{ $errors->has('nama_pelanggaran') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                @error('nama_pelanggaran') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Deskripsi <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                </label>
                <textarea id="deskripsi" name="deskripsi" rows="3"
                          class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white resize-none">{{ old('deskripsi', $jenisPelanggaran->deskripsi) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                {{-- Kategori --}}
                <div>
                    <label for="kategori" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Kategori <span class="text-red-500">*</span>
                    </label>
                    <select id="kategori" name="kategori"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                                   {{ $errors->has('kategori') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                        <option value="ringan" {{ old('kategori', $jenisPelanggaran->kategori) === 'ringan' ? 'selected' : '' }}>Ringan</option>
                        <option value="sedang" {{ old('kategori', $jenisPelanggaran->kategori) === 'sedang' ? 'selected' : '' }}>Sedang</option>
                        <option value="berat"  {{ old('kategori', $jenisPelanggaran->kategori) === 'berat'  ? 'selected' : '' }}>Berat</option>
                    </select>
                    @error('kategori') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Bobot Poin --}}
                <div>
                    <label for="bobot_poin" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Bobot Poin <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="bobot_poin" name="bobot_poin"
                           value="{{ old('bobot_poin', $jenisPelanggaran->bobot_poin) }}" min="1" max="100"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                                  {{ $errors->has('bobot_poin') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                    @error('bobot_poin') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Status --}}
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-2">Status <span class="text-red-500">*</span></label>
                <div class="flex gap-4">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="aktif"
                               {{ old('status', $jenisPelanggaran->status) === 'aktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 focus:ring-blue-400">
                        <span class="text-sm text-slate-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="radio" name="status" value="nonaktif"
                               {{ old('status', $jenisPelanggaran->status) === 'nonaktif' ? 'checked' : '' }}
                               class="w-4 h-4 text-blue-600 focus:ring-blue-400">
                        <span class="text-sm text-slate-700">Nonaktif</span>
                    </label>
                </div>
                @error('status') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                    Perbarui
                </button>
                <a href="{{ route('jenis-pelanggaran.index') }}"
                   class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
