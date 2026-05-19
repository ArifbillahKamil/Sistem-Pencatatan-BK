@extends('layouts.app')

@section('title', 'Catat Pelanggaran')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('transaksi.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Catat Pelanggaran Baru</h1>
        <p class="text-slate-500 text-sm">Poin siswa akan diperbarui otomatis setelah disimpan</p>
    </div>
</div>

{{-- SP Auto-generate Info --}}
<div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 mb-5 flex gap-3 items-start">
    <svg class="w-5 h-5 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <p class="text-sm text-blue-700">
        <span class="font-semibold">Surat Peringatan otomatis:</span>
        SP1 diterbitkan saat poin ≥ 25, SP2 saat ≥ 50, SP3 saat ≥ 75.
    </p>
</div>

<div class="max-w-2xl">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <form action="{{ route('transaksi.store') }}" method="POST" class="space-y-5">
            @csrf

            {{-- Siswa --}}
            <div>
                <label for="id_siswa" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Siswa <span class="text-red-500">*</span>
                </label>
                <select id="id_siswa" name="id_siswa"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                               {{ $errors->has('id_siswa') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                    <option value="">-- Pilih Siswa --</option>
                    @foreach($siswaList as $s)
                    <option value="{{ $s->id_siswa }}" {{ old('id_siswa') == $s->id_siswa ? 'selected' : '' }}>
                        {{ $s->nama_siswa }} — {{ $s->kelas->nama_kelas ?? '-' }} ({{ $s->total_poin }} poin)
                    </option>
                    @endforeach
                </select>
                @error('id_siswa') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            {{-- Jenis Pelanggaran --}}
            <div>
                <label for="id_jenis" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Jenis Pelanggaran <span class="text-red-500">*</span>
                </label>
                <select id="id_jenis" name="id_jenis"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                               {{ $errors->has('id_jenis') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                    <option value="">-- Pilih Jenis Pelanggaran --</option>
                    @foreach($jenisList as $j)
                    <option value="{{ $j->id_jenis }}" {{ old('id_jenis') == $j->id_jenis ? 'selected' : '' }}>
                        [{{ ucfirst($j->kategori) }} · {{ $j->bobot_poin }} poin] {{ $j->nama_pelanggaran }}
                    </option>
                    @endforeach
                </select>
                @error('id_jenis') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tanggal Kejadian --}}
                <div>
                    <label for="tanggal_kejadian" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Tanggal Kejadian <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="tanggal_kejadian" name="tanggal_kejadian"
                           value="{{ old('tanggal_kejadian', date('Y-m-d')) }}"
                           max="{{ date('Y-m-d') }}"
                           class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400
                                  {{ $errors->has('tanggal_kejadian') ? 'border-red-400 bg-red-50' : 'border-slate-300 bg-white' }}">
                    @error('tanggal_kejadian') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Waktu Kejadian --}}
                <div>
                    <label for="waktu_kejadian" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Waktu Kejadian <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                    </label>
                    <input type="time" id="waktu_kejadian" name="waktu_kejadian"
                           value="{{ old('waktu_kejadian') }}"
                           class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
                </div>
            </div>

            {{-- Saksi --}}
            <div>
                <label for="saksi" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Saksi <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                </label>
                <input type="text" id="saksi" name="saksi" value="{{ old('saksi') }}"
                       placeholder="Nama saksi yang menyaksikan"
                       class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            </div>

            {{-- Keterangan --}}
            <div>
                <label for="keterangan" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Keterangan <span class="text-slate-400 text-xs font-normal">(opsional)</span>
                </label>
                <textarea id="keterangan" name="keterangan" rows="3"
                          placeholder="Deskripsi singkat kejadian..."
                          class="w-full px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white resize-none">{{ old('keterangan') }}</textarea>
            </div>

            {{-- Status Penanganan --}}
            <div>
                <label for="status_penanganan" class="block text-sm font-medium text-slate-700 mb-1.5">
                    Status Penanganan <span class="text-red-500">*</span>
                </label>
                <select id="status_penanganan" name="status_penanganan"
                        class="w-full px-4 py-2.5 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white
                               {{ $errors->has('status_penanganan') ? 'border-red-400 bg-red-50' : 'border-slate-300' }}">
                    <option value="belum"   {{ old('status_penanganan', 'belum') === 'belum'   ? 'selected' : '' }}>Belum Ditangani</option>
                    <option value="proses"  {{ old('status_penanganan') === 'proses'  ? 'selected' : '' }}>Dalam Proses</option>
                    <option value="selesai" {{ old('status_penanganan') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>
                @error('status_penanganan') <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
                    Simpan Pelanggaran
                </button>
                <a href="{{ route('transaksi.index') }}"
                   class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
