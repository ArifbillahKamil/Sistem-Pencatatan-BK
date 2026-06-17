@props(['size' => 'sm'])

@if($size === 'sm')
    <div class="flex items-center gap-3">
        <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" class="h-10 w-auto" onerror="this.style.display='none'">
        <span class="font-bold text-slate-800 text-lg">SMPN 16 GRESIK</span>
    </div>
@elseif($size === 'lg')
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo" class="h-20 w-auto mx-auto mb-4" onerror="this.style.display='none'">
        <h1 class="text-3xl font-bold text-slate-800 mb-2">UPT SMPN 16 GRESIK</h1>
        <p class="text-slate-500 text-lg">Sistem Manajemen Pelanggaran dan Poin Siswa</p>
        <hr class="mt-8 border-slate-200">
    </div>
@endif
