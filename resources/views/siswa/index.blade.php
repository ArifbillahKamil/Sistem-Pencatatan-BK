@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Data Siswa</h1>
        <p class="text-slate-500 text-sm mt-1">Total <span class="font-semibold text-slate-700">{{ $siswa->count() }}</span> siswa terdaftar</p>
    </div>
    <a href="{{ route('siswa.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Siswa
    </a>
</div>

{{-- Filter & Search --}}
<form method="GET" action="{{ route('siswa.index') }}" class="bg-white rounded-2xl shadow-sm border border-slate-100 p-4 mb-5">
    <div class="flex flex-col sm:flex-row gap-3">
        <input type="text" name="search" value="{{ request('search') }}"
               placeholder="Cari nama siswa atau NISN..."
               class="flex-1 px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <select name="kelas" class="px-4 py-2.5 border border-slate-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 bg-white sm:w-48">
            <option value="">-- Semua Kelas --</option>
            @foreach($kelasList as $k)
            <option value="{{ $k->id_kelas }}" {{ request('kelas') == $k->id_kelas ? 'selected' : '' }}>
                {{ $k->nama_kelas }}
            </option>
            @endforeach
        </select>
        <button type="submit"
                class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition">
            Cari
        </button>
        @if(request()->hasAny(['search', 'kelas']))
        <a href="{{ route('siswa.index') }}"
           class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-medium rounded-xl transition text-center">
            Reset
        </a>
        @endif
    </div>
</form>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($siswa->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <p class="font-medium text-slate-500">Tidak ada data siswa ditemukan</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">No</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">NISN</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Siswa</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">L/P</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Kelas</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Poin</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Status SP</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($siswa as $i => $s)
                @php
                    $poin = $s->total_poin;
                    $poinTahunIni = $s->getTotalPoinTahunIni();
                    $predikat = \App\Helpers\AcademicYearHelper::getPredikat($poinTahunIni);
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
                    <td class="px-4 py-3 text-slate-400">{{ $i + 1 }}</td>
                    <td class="px-4 py-3 font-mono text-slate-600 text-xs">{{ $s->nisn }}</td>
                    <td class="px-4 py-3 font-medium text-slate-800">{{ $s->nama_siswa }}</td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium
                            {{ $s->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-600' }}">
                            {{ $s->jenis_kelamin === 'L' ? 'L' : 'P' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-700">
                            {{ $s->kelas->nama_kelas ?? '-' }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="{{ $poinColor }}">{{ $poin }}</span>
                        <div class="mt-1">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-{{ $predikat['color'] }}-100 text-{{ $predikat['color'] }}-800">
                                {{ $predikat['label'] }}
                            </span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $spBadge }}">
                            {{ $sp ?? 'Aman' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex items-center justify-center gap-1.5">
                            <a href="{{ route('siswa.show', $s->id_siswa) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition"
                               title="Rekap Pelanggaran">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Rekap
                            </a>
                            <a href="{{ route('siswa.edit', $s->id_siswa) }}"
                               class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('siswa.destroy', $s->id_siswa) }}" method="POST"
                                  onsubmit="return confirm('Hapus data siswa {{ $s->nama_siswa }}?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition">
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
    @endif
</div>

@endsection
