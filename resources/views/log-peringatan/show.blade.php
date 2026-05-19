@extends('layouts.app')

@section('title', 'Riwayat SP — ' . $siswa->nama_siswa)

@section('content')

<div class="mb-6 flex items-center gap-3">
    <a href="{{ route('log-peringatan.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Riwayat SP — {{ $siswa->nama_siswa }}</h1>
        <p class="text-slate-500 text-sm">{{ $siswa->kelas->nama_kelas ?? '-' }} · NISN: {{ $siswa->nisn }}</p>
    </div>
    <div class="ml-auto">
        <a href="{{ route('siswa.show', $siswa->id_siswa) }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-xl transition">
            Rekap Lengkap →
        </a>
    </div>
</div>

{{-- Info Siswa --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-500 mb-1">Total Poin Saat Ini</p>
        @php $poin = $siswa->total_poin; @endphp
        <p class="text-2xl font-bold {{ $poin > 60 ? 'text-rose-600' : ($poin > 40 ? 'text-red-500' : ($poin > 20 ? 'text-amber-500' : 'text-slate-800')) }}">
            {{ $poin }}
        </p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-500 mb-1">Total SP Diterima</p>
        <p class="text-2xl font-bold text-slate-800">{{ $siswa->logPeringatan->count() }}</p>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-500 mb-1">SP Aktif</p>
        <p class="text-2xl font-bold text-blue-700">{{ $siswa->logPeringatan->where('status','aktif')->count() }}</p>
    </div>
</div>

{{-- SP Timeline --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100">
    <div class="px-5 py-4 border-b border-slate-100">
        <h2 class="font-semibold text-slate-800">Timeline Surat Peringatan</h2>
    </div>

    @if($siswa->logPeringatan->isEmpty())
    <div class="py-12 text-center text-slate-400">
        <p class="text-sm">Siswa ini belum memiliki surat peringatan</p>
    </div>
    @else
    <div class="p-5">
        <div class="relative">
            {{-- Vertical line --}}
            <div class="absolute left-5 top-0 bottom-0 w-px bg-slate-200"></div>

            <div class="space-y-6">
                @foreach($siswa->logPeringatan as $log)
                @php
                    $spColor = match($log->status_sp) { 'SP3' => 'bg-rose-500', 'SP2' => 'bg-red-500', default => 'bg-amber-400' };
                    $spRing  = match($log->status_sp) { 'SP3' => 'ring-rose-200', 'SP2' => 'ring-red-200', default => 'ring-amber-200' };
                    $spBadge = match($log->status_sp) { 'SP3' => 'bg-rose-100 text-rose-700', 'SP2' => 'bg-red-100 text-red-600', default => 'bg-amber-100 text-amber-700' };
                @endphp
                <div class="relative pl-14">
                    {{-- Dot --}}
                    <div class="absolute left-3.5 top-1 w-3 h-3 rounded-full {{ $spColor }} ring-4 {{ $spRing }} -translate-x-1/2"></div>

                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-bold {{ $spBadge }}">{{ $log->status_sp }}</span>
                                    <span class="text-xs text-slate-500">{{ $log->tanggal_terbit->format('d F Y') }}</span>
                                </div>
                                <p class="text-sm text-slate-700">
                                    Total poin saat diterbitkan:
                                    <span class="font-bold text-slate-900">{{ $log->total_poin_saat_sp }} poin</span>
                                </p>
                                @if($log->keterangan_sp)
                                <p class="text-xs text-slate-500 mt-1 italic">{{ $log->keterangan_sp }}</p>
                                @endif
                            </div>
                            <div class="flex-shrink-0 flex flex-col items-end gap-2">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $log->status === 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500' }}">
                                    {{ ucfirst($log->status) }}
                                </span>
                                <form action="{{ route('log-peringatan.toggle', $log->id_log) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="text-xs font-medium px-2.5 py-1 rounded-lg transition
                                                   {{ $log->status === 'aktif' ? 'text-emerald-600 bg-emerald-50 hover:bg-emerald-100' : 'text-slate-600 bg-slate-100 hover:bg-slate-200' }}">
                                        {{ $log->status === 'aktif' ? '✓ Selesai' : '↺ Aktifkan' }}
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
