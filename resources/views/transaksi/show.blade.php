@extends('layouts.app')

@section('title', 'Detail Pelanggaran')

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('transaksi.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Pelanggaran</h1>
        <p class="text-slate-500 text-sm">ID Transaksi #{{ $transaksi->id_transaksi }}</p>
    </div>
    <div class="ml-auto flex gap-2">
        <a href="{{ route('transaksi.edit', $transaksi->id_transaksi) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
    </div>
</div>

<div class="max-w-2xl space-y-5">

    {{-- Siswa Info --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Informasi Siswa</h2>
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-bold text-lg flex-shrink-0">
                {{ strtoupper(substr($transaksi->siswa->nama_siswa ?? 'S', 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-slate-800">{{ $transaksi->siswa->nama_siswa ?? '-' }}</p>
                <p class="text-sm text-slate-500">{{ $transaksi->siswa->kelas->nama_kelas ?? '-' }} · NISN: {{ $transaksi->siswa->nisn ?? '-' }}</p>
                <p class="text-sm mt-1">
                    <span class="text-slate-500">Total Poin Saat Ini:</span>
                    <span class="font-bold {{ $transaksi->siswa->total_poin > 50 ? 'text-red-600' : ($transaksi->siswa->total_poin > 25 ? 'text-amber-600' : 'text-slate-700') }}">
                        {{ $transaksi->siswa->total_poin }}
                    </span>
                </p>
            </div>
        </div>
    </div>

    {{-- Detail Pelanggaran --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
        <h2 class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">Detail Pelanggaran</h2>
        @php
            $kat = $transaksi->jenisPelanggaran->kategori ?? 'ringan';
            $katBadge = match($kat) { 'berat' => 'bg-red-100 text-red-700', 'sedang' => 'bg-amber-100 text-amber-700', default => 'bg-green-100 text-green-700' };
            $statusBadge = match($transaksi->status_penanganan) { 'selesai' => 'bg-emerald-100 text-emerald-700', 'proses' => 'bg-yellow-100 text-yellow-700', default => 'bg-slate-100 text-slate-500' };
            $statusLabel = match($transaksi->status_penanganan) { 'selesai' => 'Selesai', 'proses' => 'Dalam Proses', default => 'Belum Ditangani' };
        @endphp
        <dl class="space-y-3 text-sm">
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Jenis Pelanggaran</dt>
                <dd class="font-medium text-slate-800">{{ $transaksi->jenisPelanggaran->nama_pelanggaran ?? '-' }}</dd>
            </div>
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Kategori</dt>
                <dd><span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $katBadge }}">{{ ucfirst($kat) }}</span></dd>
            </div>
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Bobot Poin</dt>
                <dd class="font-bold text-slate-800">+{{ $transaksi->jenisPelanggaran->bobot_poin ?? 0 }} poin</dd>
            </div>
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Tanggal Kejadian</dt>
                <dd class="text-slate-800">{{ $transaksi->tanggal_kejadian->format('d F Y') }}</dd>
            </div>
            @if($transaksi->waktu_kejadian)
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Waktu</dt>
                <dd class="text-slate-800">{{ $transaksi->waktu_kejadian }}</dd>
            </div>
            @endif
            @if($transaksi->saksi)
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Saksi</dt>
                <dd class="text-slate-800">{{ $transaksi->saksi }}</dd>
            </div>
            @endif
            @if($transaksi->keterangan)
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Keterangan</dt>
                <dd class="text-slate-700">{{ $transaksi->keterangan }}</dd>
            </div>
            @endif
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Status</dt>
                <dd><span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">{{ $statusLabel }}</span></dd>
            </div>
            <div class="flex gap-3 border-t border-slate-100 pt-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Dicatat oleh</dt>
                <dd class="text-slate-700">{{ $transaksi->pelapor->nama_lengkap ?? '-' }}</dd>
            </div>
            <div class="flex gap-3">
                <dt class="w-36 text-slate-500 flex-shrink-0">Dicatat pada</dt>
                <dd class="text-slate-700">{{ $transaksi->created_at->format('d F Y, H:i') }}</dd>
            </div>
        </dl>
    </div>

</div>

@endsection
