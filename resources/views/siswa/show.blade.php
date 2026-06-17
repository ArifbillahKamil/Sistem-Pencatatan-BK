@extends('layouts.app')

@section('title', 'Rekap Pelanggaran — ' . $siswa->nama_siswa)

@section('content')

{{-- Header --}}
<div class="mb-6 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <a href="{{ route('siswa.index') }}" class="p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Rekap Pelanggaran</h1>
            <p class="text-slate-500 text-sm">{{ $siswa->nama_siswa }} — {{ $siswa->kelas->nama_kelas ?? '-' }}</p>
        </div>
    </div>
    <a href="{{ route('pdf.siswa', $siswa->id_siswa) }}" target="_blank"
       class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-xl transition shadow-sm">
        📄 Export PDF
    </a>
</div>

{{-- Info Card --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-500 mb-1">NISN</p>
        <p class="font-mono font-semibold text-slate-800">{{ $siswa->nisn }}</p>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-500 mb-1">Total Poin Pelanggaran</p>
        @php
            $poin = $siswa->total_poin;
            $poinTahunIni = $siswa->getTotalPoinTahunIni();
            $predikat = \App\Helpers\AcademicYearHelper::getPredikat($poinTahunIni);
            $poinColor = $poin > 60 ? 'text-rose-600' : ($poin > 40 ? 'text-red-500' : ($poin > 20 ? 'text-amber-500' : 'text-slate-800'));
        @endphp
        <div class="flex items-center gap-3">
            <p class="text-2xl font-bold {{ $poinColor }}">{{ $poin }} <span class="text-sm font-normal text-slate-400">poin</span></p>
            <span class="inline-block px-2.5 py-1 rounded text-xs font-semibold bg-{{ $predikat['color'] }}-100 text-{{ $predikat['color'] }}-800">
                {{ $predikat['label'] }}
            </span>
        </div>
    </div>
    <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
        <p class="text-xs text-slate-500 mb-1">Status SP Aktif</p>
        @php
            $spAktif = $siswa->logPeringatan->where('status','aktif')->sortByDesc(fn($l) => ['SP1'=>1,'SP2'=>2,'SP3'=>3][$l->status_sp])->first();
            $sp = $spAktif?->status_sp;
            $spColor = match($sp) { 'SP3' => 'text-rose-700', 'SP2' => 'text-red-600', 'SP1' => 'text-amber-600', default => 'text-emerald-600' };
        @endphp
        <p class="text-2xl font-bold {{ $spColor }}">{{ $sp ?? 'Aman' }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Riwayat Pelanggaran --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Riwayat Pelanggaran</h2>
            <span class="text-xs text-slate-400">{{ $siswa->transaksiPelanggaran->count() }} catatan</span>
        </div>

        @if($siswa->transaksiPelanggaran->isEmpty())
        <div class="py-10 text-center text-slate-400">
            <p class="text-sm">Belum ada catatan pelanggaran</p>
        </div>
        @else
        <div class="divide-y divide-slate-50 max-h-96 overflow-y-auto">
            @foreach($siswa->transaksiPelanggaran as $t)
            @php
                $kat = $t->jenisPelanggaran->kategori ?? 'ringan';
                $katBadge = match($kat) { 'berat' => 'bg-red-100 text-red-700', 'sedang' => 'bg-amber-100 text-amber-700', default => 'bg-green-100 text-green-700' };
            @endphp
            <div class="px-5 py-3">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800">{{ $t->jenisPelanggaran->nama_pelanggaran ?? '-' }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $t->tanggal_kejadian->format('d M Y') }}
                            @if($t->keterangan) · {{ Str::limit($t->keterangan, 50) }} @endif
                        </p>
                    </div>
                    <div class="flex-shrink-0 text-right">
                        <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $katBadge }}">{{ ucfirst($kat) }}</span>
                        <p class="text-xs font-bold text-slate-600 mt-1">+{{ $t->jenisPelanggaran->bobot_poin ?? 0 }} poin</p>
                    </div>
                </div>
                @php
                    $statusBadge = match($t->status_penanganan) { 'selesai' => 'bg-green-100 text-green-700', 'proses' => 'bg-yellow-100 text-yellow-700', default => 'bg-slate-100 text-slate-500' };
                @endphp
                <span class="mt-1.5 inline-block px-2 py-0.5 rounded text-xs {{ $statusBadge }}">
                    {{ ucfirst($t->status_penanganan) }}
                </span>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- Log Peringatan (SP) --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="font-semibold text-slate-800">Log Surat Peringatan</h2>
            <span class="text-xs text-slate-400">{{ $siswa->logPeringatan->count() }} SP</span>
        </div>

        @if($siswa->logPeringatan->isEmpty())
        <div class="py-10 text-center text-slate-400">
            <p class="text-sm">Belum ada surat peringatan</p>
        </div>
        @else
        <div class="divide-y divide-slate-50">
            @foreach($siswa->logPeringatan as $log)
            @php
                $spBadge = match($log->status_sp) { 'SP3' => 'bg-rose-100 text-rose-700', 'SP2' => 'bg-red-100 text-red-600', default => 'bg-amber-100 text-amber-700' };
                $statusBadge = $log->status === 'aktif' ? 'bg-blue-100 text-blue-700' : 'bg-slate-100 text-slate-500';
            @endphp
            <div class="px-5 py-3.5">
                <div class="flex items-center justify-between gap-3">
                    <div class="flex items-center gap-2">
                        <span class="inline-block px-2.5 py-1 rounded-lg text-xs font-bold {{ $spBadge }}">{{ $log->status_sp }}</span>
                        <div>
                            <p class="text-sm font-medium text-slate-800">Poin saat diterbitkan: {{ $log->total_poin_saat_sp }}</p>
                            <p class="text-xs text-slate-500">{{ $log->tanggal_terbit->format('d M Y') }}</p>
                        </div>
                    </div>
                    <span class="inline-block px-2 py-0.5 rounded-full text-xs font-medium {{ $statusBadge }}">
                        {{ ucfirst($log->status) }}
                    </span>
                </div>
                @if($log->keterangan_sp)
                <p class="mt-1.5 text-xs text-slate-500 italic">{{ $log->keterangan_sp }}</p>
                @endif
            </div>
            @endforeach
        </div>
        @endif
    </div>

</div>

@endsection
