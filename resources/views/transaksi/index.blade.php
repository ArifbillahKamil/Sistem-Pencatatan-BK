@extends('layouts.app')

@section('title', 'Transaksi Pelanggaran')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Transaksi Pelanggaran</h1>
        <p class="text-slate-500 text-sm mt-1">Semua catatan pelanggaran siswa</p>
    </div>
    <a href="{{ route('transaksi.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Catat Pelanggaran
    </a>
</div>

{{-- Filter --}}
<form method="GET" action="{{ route('transaksi.index') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama siswa / NISN..."
               class="flex-1 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">

        <select name="kategori" class="px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white sm:w-40">
            <option value="">Semua Kategori</option>
            <option value="ringan" {{ request('kategori') === 'ringan' ? 'selected' : '' }}>Ringan</option>
            <option value="sedang" {{ request('kategori') === 'sedang' ? 'selected' : '' }}>Sedang</option>
            <option value="berat"  {{ request('kategori') === 'berat'  ? 'selected' : '' }}>Berat</option>
        </select>

        <select name="status" class="px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white sm:w-40">
            <option value="">Semua Status</option>
            <option value="belum"   {{ request('status') === 'belum'   ? 'selected' : '' }}>Belum Ditangani</option>
            <option value="proses"  {{ request('status') === 'proses'  ? 'selected' : '' }}>Dalam Proses</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>

        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">Cari</button>
        @if(request()->hasAny(['search','kategori','status']))
        <a href="{{ route('transaksi.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition text-center">Reset</a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($transaksi->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <p class="font-medium text-slate-500">Belum ada catatan pelanggaran</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Siswa</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Pelanggaran</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Kategori</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Poin</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($transaksi as $t)
                @php
                    $kat = $t->jenisPelanggaran->kategori ?? 'ringan';
                    $katBadge = match($kat) { 'berat' => 'bg-red-100 text-red-700', 'sedang' => 'bg-amber-100 text-amber-700', default => 'bg-green-100 text-green-700' };
                    $statusBadge = match($t->status_penanganan) { 'selesai' => 'bg-emerald-100 text-emerald-700', 'proses' => 'bg-yellow-100 text-yellow-700', default => 'bg-slate-100 text-slate-500' };
                    $statusLabel = match($t->status_penanganan) { 'selesai' => 'Selesai', 'proses' => 'Proses', default => 'Belum' };
                @endphp
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">
                        {{ $t->tanggal_kejadian->format('d M Y') }}
                        @if($t->waktu_kejadian)
                            <p class="text-xs text-slate-400">{{ $t->waktu_kejadian }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        <p class="font-medium text-slate-800">{{ $t->siswa->nama_siswa ?? '-' }}</p>
                        <p class="text-xs text-slate-400">{{ $t->siswa->kelas->nama_kelas ?? '-' }}</p>
                    </td>
                    <td class="px-4 py-3">
                        <p class="text-slate-700">{{ $t->jenisPelanggaran->nama_pelanggaran ?? '-' }}</p>
                        @if($t->keterangan)
                            <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($t->keterangan, 50) }}</p>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $katBadge }}">{{ ucfirst($kat) }}</span>
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-slate-700">
                        +{{ $t->jenisPelanggaran->bobot_poin ?? 0 }}
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('transaksi.show', $t->id_transaksi) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                            <a href="{{ route('transaksi.edit', $t->id_transaksi) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('transaksi.destroy', $t->id_transaksi) }}" method="POST"
                                  onsubmit="return confirm('Hapus catatan pelanggaran ini? Poin siswa akan diperbarui otomatis.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($transaksi->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $transaksi->links('pagination::tailwind') }}
    </div>
    @endif
    @endif
</div>

@endsection
