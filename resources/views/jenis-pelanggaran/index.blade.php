@extends('layouts.app')

@section('title', 'Jenis Pelanggaran')

@section('content')

<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Jenis Pelanggaran</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola kategori dan bobot poin pelanggaran</p>
    </div>
    <a href="{{ route('jenis-pelanggaran.create') }}"
       class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Jenis
    </a>
</div>

{{-- Summary Cards --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    @php
        $ringan = $jenisList->where('kategori','ringan')->count();
        $sedang = $jenisList->where('kategori','sedang')->count();
        $berat  = $jenisList->where('kategori','berat')->count();
    @endphp
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
            <span class="text-green-600 text-xs font-bold">R</span>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $ringan }}</p>
            <p class="text-xs text-slate-500">Pelanggaran Ringan</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center flex-shrink-0">
            <span class="text-amber-600 text-xs font-bold">S</span>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $sedang }}</p>
            <p class="text-xs text-slate-500">Pelanggaran Sedang</p>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
            <span class="text-red-600 text-xs font-bold">B</span>
        </div>
        <div>
            <p class="text-xl font-bold text-slate-800">{{ $berat }}</p>
            <p class="text-xs text-slate-500">Pelanggaran Berat</p>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
    @if($jenisList->isEmpty())
    <div class="py-16 text-center text-slate-400">
        <svg class="w-12 h-12 mx-auto mb-3 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        <p class="font-medium text-slate-500">Belum ada jenis pelanggaran</p>
        <a href="{{ route('jenis-pelanggaran.create') }}" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Tambah sekarang</a>
    </div>
    @else
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">No</th>
                    <th class="px-5 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wide">Nama Pelanggaran</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Kategori</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Bobot Poin</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Pemakaian</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                    <th class="px-5 py-3 text-center text-xs font-semibold text-slate-500 uppercase tracking-wide">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @foreach($jenisList as $i => $j)
                @php
                    $katBadge = match($j->kategori) {
                        'berat'  => 'bg-red-100 text-red-700',
                        'sedang' => 'bg-amber-100 text-amber-700',
                        default  => 'bg-green-100 text-green-700',
                    };
                    $poinColor = $j->bobot_poin >= 25 ? 'text-red-600 font-bold' :
                                 ($j->bobot_poin >= 15 ? 'text-amber-600 font-semibold' : 'text-slate-700');
                @endphp
                <tr class="hover:bg-slate-50 transition">
                    <td class="px-5 py-3.5 text-slate-400">{{ $i + 1 }}</td>
                    <td class="px-5 py-3.5">
                        <p class="font-medium text-slate-800">{{ $j->nama_pelanggaran }}</p>
                        @if($j->deskripsi)
                        <p class="text-xs text-slate-400 mt-0.5">{{ Str::limit($j->deskripsi, 60) }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium {{ $katBadge }}">
                            {{ ucfirst($j->kategori) }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-center {{ $poinColor }}">{{ $j->bobot_poin }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="font-medium text-slate-700">{{ $j->transaksi_pelanggaran_count }}</span>
                        <span class="text-xs text-slate-400 ml-0.5">kali</span>
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        @if($j->status === 'aktif')
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Aktif</span>
                        @else
                        <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-500">Nonaktif</span>
                        @endif
                    </td>
                    <td class="px-5 py-3.5 text-center">
                        <div class="flex items-center justify-center gap-2">
                            <a href="{{ route('jenis-pelanggaran.edit', $j->id_jenis) }}"
                               class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="{{ route('jenis-pelanggaran.destroy', $j->id_jenis) }}" method="POST"
                                  onsubmit="return confirm('Hapus jenis pelanggaran ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition"
                                        {{ $j->transaksi_pelanggaran_count > 0 ? 'disabled title=Tidak dapat dihapus karena sudah digunakan' : '' }}>
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
