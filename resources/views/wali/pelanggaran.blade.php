@extends('layouts.app')

@section('title', 'Riwayat Pelanggaran')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Riwayat Pelanggaran</h1>
    @if($kelas)
    <p class="text-slate-500 text-sm mt-1">Kelas <span class="font-semibold text-slate-700">{{ $kelas->nama_kelas }}</span> — hanya dapat dilihat</p>
    @endif
</div>

@if(! $kelas)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
    <p class="text-amber-800 font-medium">Anda belum ditetapkan sebagai wali kelas manapun.</p>
</div>
@else

{{-- Search --}}
<form method="GET" action="{{ route('wali.pelanggaran') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama siswa..."
               class="flex-1 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">Cari</button>
        @if(request('search'))
        <a href="{{ route('wali.pelanggaran') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl transition">Reset</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($transaksi->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <p class="font-medium text-slate-500">Belum ada catatan pelanggaran untuk kelas ini</p>
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
                    <td class="px-4 py-3 text-slate-600 whitespace-nowrap">{{ $t->tanggal_kejadian->format('d M Y') }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $t->siswa->nama_siswa ?? '-' }}</td>
                    <td class="px-4 py-3 text-slate-700">{{ $t->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $katBadge }}">{{ ucfirst($kat) }}</span>
                    </td>
                    <td class="px-4 py-3 text-center font-bold text-slate-700">+{{ $t->jenisPelanggaran->bobot_poin ?? 0 }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">{{ $statusLabel }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Read-only notice --}}
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50 flex items-center justify-between">
        <p class="text-xs text-slate-400">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Mode baca saja — penambahan data dilakukan oleh Guru BK
        </p>
        @if($transaksi->hasPages())
        {{ $transaksi->links('pagination::tailwind') }}
        @endif
    </div>
    @endif
</div>
@endif

@endsection
