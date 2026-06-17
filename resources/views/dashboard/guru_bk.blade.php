@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Hero Banner --}}
<x-school-header size="lg" />

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500 text-sm mt-1">Selamat datang, <span class="font-medium text-blue-600">{{ auth()->user()->nama_lengkap }}</span> — Ringkasan sistem hari ini</p>
</div>

{{-- ── Stats Cards ──────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-5 gap-4 mb-6">

    {{-- Total Siswa --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalSiswa }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Total Siswa</p>
        </div>
    </div>

    {{-- Pelanggaran Hari Ini --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalPelanggaranHariIni }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Pelanggaran Hari Ini</p>
        </div>
    </div>

    {{-- SP1 --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-100 flex items-center justify-center flex-shrink-0">
            <span class="text-yellow-600 font-bold text-sm">SP1</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $siswaSp1 }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Siswa SP1 Aktif</p>
        </div>
    </div>

    {{-- SP2 --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <span class="text-red-500 font-bold text-sm">SP2</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $siswaSp2 }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Siswa SP2 Aktif</p>
        </div>
    </div>

    {{-- SP3 --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
            <span class="text-rose-700 font-bold text-sm">SP3</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $siswaSp3 }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Siswa SP3 Aktif</p>
        </div>
    </div>

</div>

{{-- ── Bottom Panels ─────────────────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Pelanggaran Terbaru --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Pelanggaran Terbaru</h2>
            <a href="{{ route('transaksi.index') }}"
               class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat Semua →</a>
        </div>

        @if($pelanggaranTerbaru->isEmpty())
        <div class="px-5 py-10 text-center text-slate-400">
            <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <p class="text-sm">Belum ada pelanggaran tercatat</p>
        </div>
        @else
        <div class="divide-y divide-slate-50">
            @foreach($pelanggaranTerbaru as $t)
            <div class="flex items-center gap-3 px-5 py-3">
                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr($t->siswa->nama_siswa ?? 'S', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ $t->siswa->nama_siswa ?? '-' }}</p>
                    <p class="text-xs text-slate-500 truncate">{{ $t->jenisPelanggaran->nama_pelanggaran ?? '-' }}</p>
                </div>
                <div class="text-right flex-shrink-0">
                    @php
                        $kategori = $t->jenisPelanggaran->kategori ?? 'ringan';
                        $badge = match($kategori) {
                            'berat'  => 'bg-red-100 text-red-700',
                            'sedang' => 'bg-amber-100 text-amber-700',
                            default  => 'bg-green-100 text-green-700',
                        };
                    @endphp
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $badge }}">
                        {{ ucfirst($kategori) }}
                    </span>
                    <p class="text-xs text-slate-400 mt-1">{{ $t->tanggal_kejadian->format('d/m/Y') }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Top 5 Siswa Poin Tertinggi --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
            <h2 class="font-semibold text-slate-800">Siswa Poin Tertinggi</h2>
            <a href="{{ route('siswa.index') }}"
               class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat Semua →</a>
        </div>

        @if($siswaPoinsTop->isEmpty())
        <div class="px-5 py-10 text-center text-slate-400">
            <p class="text-sm">Belum ada data siswa</p>
        </div>
        @else
        <div class="divide-y divide-slate-50">
            @foreach($siswaPoinsTop as $i => $s)
            <div class="flex items-center gap-3 px-5 py-3">
                {{-- Rank --}}
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0
                    {{ $i === 0 ? 'bg-amber-400 text-white' : ($i === 1 ? 'bg-slate-300 text-slate-700' : ($i === 2 ? 'bg-orange-300 text-white' : 'bg-slate-100 text-slate-500')) }}">
                    {{ $i + 1 }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-slate-800 truncate">{{ $s->nama_siswa }}</p>
                    <p class="text-xs text-slate-500">{{ $s->kelas->nama_kelas ?? '-' }}</p>
                </div>
                <div class="flex-shrink-0 text-right">
                    @php
                        $poin = $s->total_poin;
                        $colorPoin = $poin > 60 ? 'text-rose-600' : ($poin > 40 ? 'text-red-500' : ($poin > 20 ? 'text-amber-500' : 'text-slate-700'));
                    @endphp
                    <span class="text-base font-bold {{ $colorPoin }}">{{ $poin }}</span>
                    <p class="text-xs text-slate-400">poin</p>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection
