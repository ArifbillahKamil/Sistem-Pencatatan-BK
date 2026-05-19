@extends('layouts.app')

@section('title', 'Status SP Siswa')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Status SP Siswa</h1>
    @if($kelas)
    <p class="text-slate-500 text-sm mt-1">Kelas <span class="font-semibold text-slate-700">{{ $kelas->nama_kelas }}</span> — hanya dapat dilihat</p>
    @endif
</div>

@if(! $kelas)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
    <p class="text-amber-800 font-medium">Anda belum ditetapkan sebagai wali kelas manapun.</p>
</div>
@else

{{-- Filter --}}
<form method="GET" action="{{ route('wali.sp') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex gap-3">
        <select name="status_sp" class="px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white">
            <option value="">Semua Level SP</option>
            <option value="SP1" {{ request('status_sp') === 'SP1' ? 'selected' : '' }}>SP1</option>
            <option value="SP2" {{ request('status_sp') === 'SP2' ? 'selected' : '' }}>SP2</option>
            <option value="SP3" {{ request('status_sp') === 'SP3' ? 'selected' : '' }}>SP3</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">Filter</button>
        @if(request('status_sp'))
        <a href="{{ route('wali.sp') }}" class="px-5 py-2.5 bg-slate-100 text-slate-700 text-sm font-medium rounded-xl transition">Reset</a>
        @endif
    </div>
</form>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($logs->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <p class="font-medium text-slate-500">Tidak ada surat peringatan{{ request('status_sp') ? ' untuk ' . request('status_sp') : '' }}</p>
        <p class="text-xs text-slate-400 mt-1">Semua siswa kelas ini dalam kondisi aman 🎉</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Siswa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Level SP</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Poin Saat SP</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Poin Saat Ini</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal Terbit</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($logs as $log)
                @php
                    $spBadge = match($log->status_sp) { 'SP3' => 'bg-rose-100 text-rose-700 font-bold', 'SP2' => 'bg-red-100 text-red-600 font-bold', default => 'bg-amber-100 text-amber-700 font-bold' };
                    $statusBadge = $log->status === 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500';
                    $poin = $log->siswa->total_poin ?? 0;
                    $poinColor = $poin > 60 ? 'text-rose-600 font-bold' : ($poin > 40 ? 'text-red-500 font-semibold' : ($poin > 20 ? 'text-amber-500' : 'text-slate-700'));
                @endphp
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $log->siswa->nama_siswa ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs {{ $spBadge }}">{{ $log->status_sp }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-center font-semibold text-slate-700">{{ $log->total_poin_saat_sp }}</td>
                    <td class="px-5 py-3.5 text-center {{ $poinColor }}">{{ $poin }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $log->tanggal_terbit->format('d M Y') }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                            {{ ucfirst($log->status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50">
        <p class="text-xs text-slate-400">
            <svg class="w-3.5 h-3.5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
            Mode baca saja — perubahan status dilakukan oleh Guru BK
        </p>
    </div>
    @endif
</div>
@endif

@endsection
