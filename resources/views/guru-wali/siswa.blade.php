@extends('layouts.app')

@section('title', 'Daftar Siswa Bimbingan')

@section('content')
<div class="mb-6 flex justify-between items-end">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Daftar Siswa Bimbingan</h1>
        <p class="text-slate-500 mt-1 text-sm">Lihat daftar seluruh siswa yang menjadi bimbingan Anda.</p>
    </div>
    <a href="{{ route('guru_wali.assignment') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
        </svg>
        Kelola Penugasan
    </a>
</div>

<div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
    @if($siswa->isEmpty())
        <div class="p-10 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-slate-900 mb-2">Belum Ada Siswa</h3>
            <p class="text-slate-500 mb-6 max-w-md mx-auto">Anda belum memilih atau ditugaskan untuk membimbing siswa manapun. Silakan klik tombol "Kelola Penugasan" untuk mulai memilih siswa.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 rounded-tl-lg">Nama Siswa</th>
                        <th scope="col" class="px-6 py-4">NISN</th>
                        <th scope="col" class="px-6 py-4">Kelas</th>
                        <th scope="col" class="px-6 py-4">Total Poin</th>
                        <th scope="col" class="px-6 py-4 text-right rounded-tr-lg">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($siswa as $s)
                    @php
                        $poinTahunIni = $s->getTotalPoinTahunIni();
                        $predikat = \App\Helpers\AcademicYearHelper::getPredikat($poinTahunIni);
                    @endphp
                    <tr class="bg-white hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-medium text-slate-900">
                            {{ $s->nama_siswa }}
                        </td>
                        <td class="px-6 py-4 text-slate-600">
                            {{ $s->nisn }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-2.5 py-1 bg-slate-100 text-slate-700 rounded-md text-xs font-medium border border-slate-200">
                                {{ $s->kelas->nama_kelas }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-2 h-2 rounded-full {{ $s->total_poin >= 75 ? 'bg-red-500' : ($s->total_poin >= 50 ? 'bg-orange-500' : ($s->total_poin >= 25 ? 'bg-yellow-500' : 'bg-emerald-500')) }}"></div>
                                <span class="font-bold {{ $s->total_poin >= 75 ? 'text-red-600' : ($s->total_poin >= 50 ? 'text-orange-600' : ($s->total_poin >= 25 ? 'text-yellow-600' : 'text-slate-700')) }}">
                                    {{ $s->total_poin }}
                                </span>
                            </div>
                            <div class="mt-1">
                                <span class="inline-block px-2 py-0.5 rounded text-[10px] font-medium bg-{{ $predikat['color'] }}-100 text-{{ $predikat['color'] }}-800">
                                    {{ $predikat['label'] }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('guru_wali.siswa.detail', $s->id_siswa) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 rounded-lg transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
