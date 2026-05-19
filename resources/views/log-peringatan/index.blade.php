@extends('layouts.app')

@section('title', 'Log Peringatan (SP)')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Log Surat Peringatan</h1>
    <p class="text-slate-500 text-sm mt-1">Riwayat surat peringatan yang diterbitkan secara otomatis</p>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="text-amber-600 font-bold text-sm">SP1</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $countSp1 }}</p>
            <p class="text-xs text-slate-500">SP1 Aktif</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <span class="text-red-600 font-bold text-sm">SP2</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $countSp2 }}</p>
            <p class="text-xs text-slate-500">SP2 Aktif</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0">
            <span class="text-rose-700 font-bold text-sm">SP3</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-slate-800">{{ $countSp3 }}</p>
            <p class="text-xs text-slate-500">SP3 Aktif</p>
        </div>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('log-peringatan.index') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama siswa atau NISN..."
               class="flex-1 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <select name="status_sp" class="px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white sm:w-36">
            <option value="">Semua Level</option>
            <option value="SP1" {{ request('status_sp') === 'SP1' ? 'selected' : '' }}>SP1</option>
            <option value="SP2" {{ request('status_sp') === 'SP2' ? 'selected' : '' }}>SP2</option>
            <option value="SP3" {{ request('status_sp') === 'SP3' ? 'selected' : '' }}>SP3</option>
        </select>
        <select name="status" class="px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white sm:w-36">
            <option value="">Semua Status</option>
            <option value="aktif"   {{ request('status') === 'aktif'   ? 'selected' : '' }}>Aktif</option>
            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
        </select>
        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">Cari</button>
        @if(request()->hasAny(['search','status_sp','status']))
        <a href="{{ route('log-peringatan.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition text-center">Reset</a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($logs->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        <p class="font-medium text-slate-500">Belum ada surat peringatan diterbitkan</p>
        <p class="text-xs text-slate-400 mt-1">SP diterbitkan otomatis saat total poin siswa mencapai 25 / 50 / 75</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Siswa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Level SP</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Poin Saat SP</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal Terbit</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($logs as $log)
                @php
                    $spBadge = match($log->status_sp) { 'SP3' => 'bg-rose-100 text-rose-700 font-bold', 'SP2' => 'bg-red-100 text-red-600 font-bold', default => 'bg-amber-100 text-amber-700 font-bold' };
                    $statusBadge = $log->status === 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500';
                @endphp
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-5 py-3.5">
                        <a href="{{ route('log-peringatan.show', $log->siswa->id_siswa) }}"
                           class="font-medium text-blue-600 hover:underline">{{ $log->siswa->nama_siswa ?? '-' }}</a>
                        <p class="text-xs text-slate-400">{{ $log->siswa->kelas->nama_kelas ?? '-' }}</p>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs {{ $spBadge }}">{{ $log->status_sp }}</span>
                    </td>
                    <td class="px-5 py-3.5 text-center font-semibold text-slate-700">{{ $log->total_poin_saat_sp }}</td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $log->tanggal_terbit->format('d M Y') }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                            {{ ucfirst($log->status) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <form action="{{ route('log-peringatan.toggle', $log->id_log) }}" method="POST">
                            @csrf
                            <button type="submit"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium rounded-lg transition
                                           {{ $log->status === 'aktif'
                                               ? 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100'
                                               : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}"
                                    title="{{ $log->status === 'aktif' ? 'Tandai Selesai' : 'Aktifkan Kembali' }}">
                                @if($log->status === 'aktif')
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Selesai
                                @else
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    Aktifkan
                                @endif
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="px-5 py-4 border-t border-slate-100">
        {{ $logs->links('pagination::tailwind') }}
    </div>
    @endif
    @endif
</div>

@endsection
