<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Manajemen Pelanggaran dan Poin Siswa SMPN 16 Gresik">
    <title>@yield('title', 'Dashboard') — Sistem BK SMPN 16 Gresik</title>

    {{-- Tailwind CSS via CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui'],
                    },
                }
            }
        }
    </script>

    {{-- Google Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar transition */
        #sidebar { transition: transform 0.25s ease; }

        /* Active nav item */
        .nav-active {
            background-color: rgba(255,255,255,0.15);
            border-left: 3px solid #fff;
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #94a3b8; border-radius: 4px; }

        /* Smooth page transitions */
        main { animation: fadeIn 0.2s ease; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(4px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @stack('styles')
</head>
<body class="bg-slate-100 text-slate-800 min-h-screen">

{{-- ══════════════════ SIDEBAR ══════════════════ --}}
<aside id="sidebar"
       class="fixed top-0 left-0 h-full w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white z-40 flex flex-col shadow-2xl
              -translate-x-full lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
        <div class="w-9 h-9 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477
                         5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0
                         3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
        </div>
        <div class="min-w-0">
            <p class="font-bold text-sm leading-tight">Sistem BK</p>
            <p class="text-blue-200 text-xs truncate">SMPN 16 Gresik</p>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">

        {{-- Dashboard (semua role) --}}
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('dashboard') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1
                         1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        @if(auth()->user()->role === 'guru_bk')
        {{-- ── GURU BK MENU ── --}}
        <div class="pt-3 pb-1 px-3">
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider">Master Data</p>
        </div>

        <a href="{{ route('kelas.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('kelas.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1
                         4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Data Kelas
        </a>

        <a href="{{ route('siswa.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('siswa.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7
                         20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002
                         0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Data Siswa
        </a>

        <a href="{{ route('jenis-pelanggaran.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('jenis-pelanggaran.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2
                         2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            Jenis Pelanggaran
        </a>

        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('users.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197"/>
            </svg>
            Manajemen User
        </a>

        <div class="pt-3 pb-1 px-3">
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider">Pencatatan</p>
        </div>

        <a href="{{ route('transaksi.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('transaksi.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333
                         -2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Transaksi Pelanggaran
        </a>

        <a href="{{ route('log-peringatan.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('log-peringatan.*') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2
                         2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4
                         17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Log Peringatan (SP)
        </a>

        @elseif(auth()->user()->role === 'wali_kelas')
        {{-- ── WALI KELAS MENU ── --}}
        <div class="pt-3 pb-1 px-3">
            <p class="text-blue-300 text-xs font-semibold uppercase tracking-wider">Kelas Saya</p>
        </div>

        <a href="{{ route('wali.siswa') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('wali.siswa') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7
                         20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002
                         0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Daftar Siswa
        </a>

        <a href="{{ route('wali.pelanggaran') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('wali.pelanggaran') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333
                         -2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            Riwayat Pelanggaran
        </a>

        <a href="{{ route('wali.sp') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-blue-100 hover:bg-white/10 transition
                  {{ request()->routeIs('wali.sp') ? 'nav-active' : '' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2
                         2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4
                         17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
            </svg>
            Status SP Siswa
        </a>
        @endif

    </nav>

    {{-- Sidebar Footer --}}
    <div class="px-4 py-4 border-t border-white/10">
        <p class="text-blue-300 text-xs text-center">Sistem BK © {{ date('Y') }}</p>
    </div>
</aside>

{{-- ══════════════════ OVERLAY (mobile) ══════════════════ --}}
<div id="overlay"
     class="fixed inset-0 bg-black/50 z-30 hidden lg:hidden"
     onclick="toggleSidebar()">
</div>

{{-- ══════════════════ MAIN WRAPPER ══════════════════ --}}
<div class="lg:pl-64 min-h-screen flex flex-col">

    {{-- ══ TOP NAVBAR ══ --}}
    <header class="sticky top-0 z-20 bg-white border-b border-slate-200 shadow-sm">
        <div class="flex items-center justify-between h-14 px-4 lg:px-6">

            {{-- Hamburger (mobile) --}}
            <button onclick="toggleSidebar()"
                    class="lg:hidden p-1.5 rounded-lg text-slate-500 hover:bg-slate-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page title (from @section) --}}
            <div class="hidden lg:flex items-center gap-2 text-slate-500 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>@yield('title', 'Dashboard')</span>
            </div>

            {{-- Right: User info + Logout --}}
            <div class="flex items-center gap-3 ml-auto">
                {{-- Role Badge --}}
                @if(auth()->user()->role === 'guru_bk')
                    <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        Guru BK
                    </span>
                @else
                    <span class="hidden sm:inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                        Wali Kelas
                    </span>
                @endif

                {{-- User Name --}}
                <div class="hidden sm:flex items-center gap-2">
                    <div class="w-7 h-7 rounded-full bg-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                    </div>
                    <span class="text-sm font-medium text-slate-700 max-w-[140px] truncate">
                        {{ auth()->user()->nama_lengkap }}
                    </span>
                </div>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-600
                                   hover:text-red-600 hover:bg-red-50 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>

        </div>
    </header>

    {{-- ══ CONTENT AREA ══ --}}
    <main class="flex-1 p-4 lg:p-6">

        {{-- Flash Messages --}}
        @include('partials.flash')

        {{-- Page Content --}}
        @yield('content')

    </main>
</div>

{{-- ══════════════════ SIDEBAR TOGGLE SCRIPT ══════════════════ --}}
<script>
    function toggleSidebar() {
        const sidebar  = document.getElementById('sidebar');
        const overlay  = document.getElementById('overlay');
        const isHidden = sidebar.classList.contains('-translate-x-full');

        sidebar.classList.toggle('-translate-x-full', !isHidden);
        overlay.classList.toggle('hidden', !isHidden);
    }
</script>

@stack('scripts')
</body>
</html>
