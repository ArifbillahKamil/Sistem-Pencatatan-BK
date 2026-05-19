@extends('layouts.app')

@section('title', 'Daftar Siswa')

@section('content')

<div class="mb-6">
    <h1 class="text-2xl font-bold text-slate-800">Daftar Siswa</h1>
    @if($kelas)
    <p class="text-slate-500 text-sm mt-1">Kelas <span class="font-semibold text-slate-700">{{ $kelas->nama_kelas }}</span> · Tingkat {{ $kelas->tingkat }}</p>
    @endif
</div>

@if(! $kelas)
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-6 text-center">
    <p class="text-amber-800 font-medium">Anda belum ditetapkan sebagai wali kelas manapun.</p>
    <p class="text-amber-600 text-sm mt-1">Hubungi Guru BK untuk mengatur data kelas Anda.</p>
</div>
@else

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($siswa->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <p class="font-medium">Belum ada siswa di kelas ini</p>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">NISN</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Siswa</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">L/P</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Tanggal Lahir</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Total Poin</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Status SP</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($siswa as $i => $s)
                @php
                    $poin = $s->total_poin;
                    $poinColor = $poin > 60 ? 'text-rose-600 font-bold' : ($poin > 40 ? 'text-red-500 font-semibold' : ($poin > 20 ? 'text-amber-500 font-semibold' : 'text-slate-700'));
                    $sp = $s->level_sp_aktif;
                    $spBadge = match($sp) { 'SP3' => 'bg-rose-100 text-rose-700', 'SP2' => 'bg-red-100 text-red-600', 'SP1' => 'bg-amber-100 text-amber-700', default => 'bg-slate-100 text-slate-500' };
                @endphp
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-5 py-3.5 text-slate-400">{{ $i + 1 }}</td>
                    <td class="px-5 py-3.5 font-mono text-slate-600 text-xs">{{ $s->nisn }}</td>
                    <td class="px-5 py-3.5 font-medium text-slate-800">{{ $s->nama_siswa }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $s->jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-600' }}">
                            {{ $s->jenis_kelamin === 'L' ? 'L' : 'P' }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-slate-600">{{ $s->tanggal_lahir?->format('d M Y') ?? '-' }}</td>
                    <td class="px-5 py-3.5 text-center {{ $poinColor }}">{{ $poin }}</td>
                    <td class="px-5 py-3.5 text-center">
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
