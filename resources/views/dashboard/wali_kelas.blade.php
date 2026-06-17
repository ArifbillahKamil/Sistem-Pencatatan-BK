@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Hero Banner --}}
<x-school-header size="lg" />

{{-- Page Header --}}
<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500 text-sm mt-1">
        Selamat datang, <span class="font-medium text-blue-600">{{ auth()->user()->nama_lengkap }}</span>
        @if($kelas)
            — Wali Kelas <span class="font-semibold text-slate-700">{{ $kelas->nama_kelas }}</span>
        @endif
    </p>
</div>

@if(! $kelas)
{{-- No kelas assigned --}}
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
    <svg class="w-10 h-10 mx-auto text-amber-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
    </svg>
    <p class="text-amber-800 font-medium">Anda belum ditetapkan sebagai wali kelas manapun.</p>
    <p class="text-amber-600 text-sm mt-1">Hubungi Guru BK untuk mengatur data kelas Anda.</p>
</div>

@else

{{-- ── Stats Cards ── --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

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
            <p class="text-xs text-slate-500 mt-0.5">Total Siswa Kelas {{ $kelas->nama_kelas }}</p>
        </div>
    </div>

    {{-- Total Pelanggaran --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalPelanggaran }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Total Pelanggaran Tercatat</p>
        </div>
    </div>

    {{-- Siswa Ber-SP --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $siswaBerSp }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Siswa Memiliki SP Aktif</p>
        </div>
    </div>

</div>

{{-- ── Tabel Siswa ── --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="flex items-center justify-between px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Daftar Siswa — Kelas {{ $kelas->nama_kelas }}</h2>
        <a href="{{ route('wali.siswa') }}"
           class="text-xs text-blue-600 hover:text-blue-800 font-medium transition">Lihat Detail →</a>
    </div>

    @if($siswa->isEmpty())
    <div class="px-5 py-10 text-center text-slate-400">
        <p class="text-sm">Belum ada siswa di kelas ini.</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-slate-500 text-xs uppercase tracking-wide">
                    <th class="px-5 py-3 text-left font-medium">No</th>
                    <th class="px-5 py-3 text-left font-medium">Nama Siswa</th>
                    <th class="px-5 py-3 text-left font-medium">NISN</th>
                    <th class="px-5 py-3 text-center font-medium">L/P</th>
                    <th class="px-5 py-3 text-center font-medium">Total Poin</th>
                    <th class="px-5 py-3 text-center font-medium">Status SP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($siswa as $i => $s)
                @php
                    $poin = $s->total_poin;
                    $poinColor = $poin > 60 ? 'text-rose-600 font-bold' :
                                 ($poin > 40 ? 'text-red-500 font-semibold' :
                                 ($poin > 20 ? 'text-amber-500 font-semibold' : 'text-slate-700'));

                    $logAktif = $s->logPeringatanAktif()->orderByRaw("FIELD(status_sp,'SP3','SP2','SP1')")->first();
                    $sp = $logAktif?->status_sp;
                    $spBadge = match($sp) {
                        'SP3'   => 'bg-rose-100 text-rose-700',
                        'SP2'   => 'bg-red-100 text-red-600',
                        'SP1'   => 'bg-amber-100 text-amber-700',
                        default => 'bg-slate-100 text-slate-500',
                    };
                @endphp
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-5 py-3 text-slate-500">{{ $i + 1 }}</td>
                    <td class="px-5 py-3 font-medium text-slate-800">{{ $s->nama_siswa }}</td>
                    <td class="px-5 py-3 text-slate-500">{{ $s->nisn }}</td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $s->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-600' }}">
                            {{ $s->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                        </span>
                    </td>
                    <td class="px-5 py-3 text-center font-semibold {{ $poinColor }}">
                        {{ $poin }}
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $spBadge }}">
                            {{ $sp ?? 'Aman' }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>

@endif

@endsection
