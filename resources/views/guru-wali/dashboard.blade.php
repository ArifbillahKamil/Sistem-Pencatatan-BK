@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

{{-- Hero Banner --}}
<x-school-header size="lg" />

<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard</h1>
    <p class="text-slate-500 mt-1">Selamat datang, <span class="font-medium text-blue-600">{{ auth()->user()->nama_lengkap }}</span> — Ringkasan siswa yang Anda bimbing</p>
</div>

{{-- Top Stats Cards --}}
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 lg:gap-6 mb-8">
    {{-- Total Siswa Bimbingan --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 leading-none">{{ $totalSiswa }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Siswa Bimbingan</p>
        </div>
    </div>

    {{-- Teladan --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-green-50 text-green-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 leading-none">{{ $predicateAman }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Siswa Teladan</p>
        </div>
    </div>

    {{-- Baik --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
            </svg>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 leading-none">{{ $predicateSiswaBaik }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Siswa Baik</p>
        </div>
    </div>

    {{-- Perlu Perhatian --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-yellow-50 text-yellow-600 flex items-center justify-center flex-shrink-0 font-bold">
            !
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 leading-none">{{ $predicatePerluPerhatian }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Perlu Perhatian</p>
        </div>
    </div>

    {{-- Dalam Pembinaan / Kasus Berat --}}
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center gap-4">
        <div class="w-12 h-12 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center flex-shrink-0 font-bold">
            !!
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800 leading-none">{{ $predicateDalamPembinaan + $predicateKasusBerat }}</p>
            <p class="text-xs font-medium text-slate-500 mt-1">Pembinaan/Berat</p>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
    <div class="px-5 py-4 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
        <h2 class="text-sm font-semibold text-slate-800">Perhatian Khusus (Poin Tertinggi)</h2>
        <a href="{{ route('guru_wali.siswa.index') }}" class="text-xs font-medium text-blue-600 hover:text-blue-700">Lihat Semua Siswa &rarr;</a>
    </div>
    
    <div class="p-0">
        @if($recentSiswa->isEmpty())
            <div class="p-8 text-center">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                    </svg>
                </div>
                <h3 class="text-sm font-medium text-slate-900 mb-1">Belum Ada Siswa</h3>
                <p class="text-sm text-slate-500">Anda belum memilih siswa bimbingan.</p>
                <div class="mt-4">
                    <a href="{{ route('guru_wali.assignment') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Kelola Siswa Bimbingan
                    </a>
                </div>
            </div>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($recentSiswa as $siswa)
                    <li class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 font-bold flex-shrink-0">
                                {{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900">{{ $siswa->nama_siswa }}</p>
                                <p class="text-xs text-slate-500">{{ $siswa->kelas->nama_kelas }} • NISN: {{ $siswa->nisn }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div class="text-right">
                                <span class="text-lg font-bold {{ $siswa->total_poin >= 75 ? 'text-red-600' : ($siswa->total_poin >= 50 ? 'text-orange-600' : ($siswa->total_poin >= 25 ? 'text-yellow-600' : 'text-slate-700')) }}">
                                    {{ $siswa->total_poin }}
                                </span>
                                <span class="text-xs text-slate-500">poin</span>
                            </div>
                            <a href="{{ route('guru_wali.siswa.detail', $siswa->id_siswa) }}" class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</div>
@endsection
