@extends('layouts.app')

@section('title', 'Detail Siswa Bimbingan')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Detail Siswa</h1>
        <p class="text-slate-500 mt-1 text-sm">Informasi lengkap dan riwayat pelanggaran.</p>
    </div>
    <a href="{{ route('guru_wali.siswa.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg hover:bg-slate-50 transition font-medium text-sm shadow-sm">
        &larr; Kembali
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    {{-- Left Column: Profile --}}
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-6 text-center border-b border-slate-100">
                <div class="w-24 h-24 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-3xl font-bold mx-auto mb-4">
                    {{ strtoupper(substr($siswa->nama_siswa, 0, 1)) }}
                </div>
                <h2 class="text-xl font-bold text-slate-900">{{ $siswa->nama_siswa }}</h2>
                <p class="text-sm text-slate-500 mt-1">{{ $siswa->nisn }}</p>
                <div class="mt-4 inline-block px-3 py-1 bg-slate-100 rounded-full text-sm font-medium text-slate-700 border border-slate-200">
                    Kelas {{ $siswa->kelas->nama_kelas }}
                </div>
            </div>
            
            <div class="p-6 bg-slate-50/50">
                <div class="grid grid-cols-2 gap-y-4 gap-x-4 text-sm">
                    <div>
                        <p class="text-slate-500 mb-1">Jenis Kelamin</p>
                        <p class="font-medium text-slate-900">{{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    <div>
                        <p class="text-slate-500 mb-1">Tgl Lahir</p>
                        <p class="font-medium text-slate-900">{{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d M Y') }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-slate-500 mb-1">No. Telp</p>
                        <p class="font-medium text-slate-900">{{ $siswa->no_telp ?: '-' }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-slate-500 mb-1">Alamat</p>
                        <p class="font-medium text-slate-900">{{ $siswa->alamat ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Point Status Card --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center">
            <h3 class="text-sm font-medium text-slate-500 mb-4">Status Poin & Pelanggaran</h3>
            <div class="text-5xl font-black mb-2 {{ $siswa->total_poin >= 75 ? 'text-red-600' : ($siswa->total_poin >= 50 ? 'text-orange-600' : ($siswa->total_poin >= 25 ? 'text-yellow-600' : 'text-emerald-600')) }}">
                {{ $siswa->total_poin }}
            </div>
            <p class="text-slate-500 text-sm mb-4">Total Poin Saat Ini</p>

            @php
                $poinTahunIni = $siswa->getTotalPoinTahunIni();
                $predikat = \App\Helpers\AcademicYearHelper::getPredikat($poinTahunIni);
            @endphp
            <div class="mb-6">
                <span class="inline-block px-3 py-1 rounded text-xs font-bold bg-{{ $predikat['color'] }}-100 text-{{ $predikat['color'] }}-800">
                    Predikat Tahun Ini: {{ $predikat['label'] }}
                </span>
            </div>

            @php $sp_aktif = $siswa->level_sp_aktif; @endphp
            @if($sp_aktif)
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg {{ $sp_aktif == 'SP3' ? 'bg-red-100 text-red-800' : ($sp_aktif == 'SP2' ? 'bg-orange-100 text-orange-800' : 'bg-yellow-100 text-yellow-800') }} font-bold border {{ $sp_aktif == 'SP3' ? 'border-red-200' : ($sp_aktif == 'SP2' ? 'border-orange-200' : 'border-yellow-200') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    Status: {{ $sp_aktif }} Aktif
                </div>
            @else
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-emerald-100 text-emerald-800 font-bold border border-emerald-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Aman (Tidak ada SP)
                </div>
            @endif
        </div>
    </div>

    {{-- Right Column: History --}}
    <div class="lg:col-span-2 space-y-6">
        
        {{-- SP History --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-red-50 text-red-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800">Riwayat Surat Peringatan (SP)</h3>
            </div>
            
            <div class="p-6">
                @if($siswa->logPeringatan->isEmpty())
                    <p class="text-slate-500 text-sm text-center">Belum pernah menerima surat peringatan.</p>
                @else
                    <div class="space-y-4">
                        @foreach($siswa->logPeringatan as $log)
                        <div class="flex p-4 rounded-xl border {{ $log->status == 'aktif' ? 'bg-orange-50 border-orange-200' : 'bg-slate-50 border-slate-200' }}">
                            <div class="flex-shrink-0 w-12 h-12 rounded-lg {{ $log->status == 'aktif' ? 'bg-orange-100 text-orange-600' : 'bg-slate-200 text-slate-500' }} flex flex-col items-center justify-center font-bold mr-4">
                                {{ $log->status_sp }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-slate-900 text-sm">SP diterbitkan tanggal {{ \Carbon\Carbon::parse($log->tanggal_sp)->format('d M Y') }}</p>
                                <p class="text-xs text-slate-600 mt-1">Status: 
                                    <span class="font-medium {{ $log->status == 'aktif' ? 'text-orange-600' : 'text-emerald-600' }}">
                                        {{ ucfirst($log->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Violation History --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-slate-800">Riwayat Pelanggaran</h3>
                <span class="ml-auto bg-slate-100 text-slate-600 py-1 px-3 rounded-full text-xs font-semibold">{{ $siswa->transaksiPelanggaran->count() }} Catatan</span>
            </div>
            
            <div class="p-0">
                @if($siswa->transaksiPelanggaran->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-slate-500 text-sm">Anak bimbingan ini belum memiliki catatan pelanggaran.</p>
                    </div>
                @else
                    <ul class="divide-y divide-slate-100">
                        @foreach($siswa->transaksiPelanggaran->sortByDesc('tanggal_kejadian') as $transaksi)
                        <li class="p-4 hover:bg-slate-50 transition">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h4 class="font-medium text-slate-900">{{ $transaksi->jenisPelanggaran->nama_pelanggaran }}</h4>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($transaksi->tanggal_kejadian)->format('d M Y') }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="px-2 py-0.5 text-[10px] font-medium rounded uppercase tracking-wider
                                            {{ $transaksi->jenisPelanggaran->kategori == 'ringan' ? 'bg-emerald-100 text-emerald-700' : 
                                              ($transaksi->jenisPelanggaran->kategori == 'sedang' ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700') }}">
                                            {{ $transaksi->jenisPelanggaran->kategori }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="font-bold text-slate-700">+{{ $transaksi->jenisPelanggaran->bobot_poin }}</span>
                                    <span class="text-xs text-slate-500">poin</span>
                                </div>
                            </div>
                            
                            @if($transaksi->keterangan)
                            <div class="mt-2 text-sm text-slate-600 bg-slate-50 p-3 rounded-lg border border-slate-100">
                                "{{ $transaksi->keterangan }}"
                            </div>
                            @endif

                            <div class="mt-3 flex items-center justify-between text-xs">
                                <span class="px-2 py-1 rounded {{ $transaksi->status_penanganan == 'Selesai' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} font-medium">
                                    {{ $transaksi->status_penanganan }}
                                </span>
                                @if($transaksi->saksi)
                                <span class="text-slate-500 flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    Saksi: {{ $transaksi->saksi }}
                                </span>
                                @endif
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
