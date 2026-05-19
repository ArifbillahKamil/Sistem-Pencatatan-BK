<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\LogPeringatan;
use App\Models\Siswa;
use App\Models\TransaksiPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'guru_bk') {
            return $this->dashboardGuruBk();
        }

        return $this->dashboardWaliKelas($user);
    }

    // ─── Dashboard Guru BK ────────────────────────────────────────────
    private function dashboardGuruBk()
    {
        $totalSiswa = Siswa::count();

        $totalPelanggaranHariIni = TransaksiPelanggaran::whereDate('tanggal_kejadian', today())->count();

        $siswaSp1 = LogPeringatan::where('status_sp', 'SP1')->where('status', 'aktif')
                                  ->distinct('id_siswa')->count('id_siswa');

        $siswaSp2 = LogPeringatan::where('status_sp', 'SP2')->where('status', 'aktif')
                                  ->distinct('id_siswa')->count('id_siswa');

        $siswaSp3 = LogPeringatan::where('status_sp', 'SP3')->where('status', 'aktif')
                                  ->distinct('id_siswa')->count('id_siswa');

        // 5 pelanggaran terbaru
        $pelanggaranTerbaru = TransaksiPelanggaran::with(['siswa', 'jenisPelanggaran'])
            ->latest()
            ->take(5)
            ->get();

        // 5 siswa dengan poin tertinggi
        $siswaPoinsTop = Siswa::with('kelas')
            ->orderByDesc('total_poin')
            ->take(5)
            ->get();

        return view('dashboard.guru_bk', compact(
            'totalSiswa',
            'totalPelanggaranHariIni',
            'siswaSp1',
            'siswaSp2',
            'siswaSp3',
            'pelanggaranTerbaru',
            'siswaPoinsTop',
        ));
    }

    // ─── Dashboard Wali Kelas ─────────────────────────────────────────
    private function dashboardWaliKelas($user)
    {
        $kelas = Kelas::where('id_user', $user->id)->first();

        if (! $kelas) {
            return view('dashboard.wali_kelas', [
                'kelas'  => null,
                'siswa'  => collect(),
                'totalSiswa'      => 0,
                'totalPelanggaran' => 0,
                'siswaBerSp'      => 0,
            ]);
        }

        $siswa = Siswa::where('id_kelas', $kelas->id_kelas)
            ->orderByDesc('total_poin')
            ->get();

        $totalSiswa = $siswa->count();

        $totalPelanggaran = TransaksiPelanggaran::whereIn('id_siswa', $siswa->pluck('id_siswa'))
            ->count();

        $siswaBerSp = LogPeringatan::whereIn('id_siswa', $siswa->pluck('id_siswa'))
            ->where('status', 'aktif')
            ->distinct('id_siswa')
            ->count('id_siswa');

        return view('dashboard.wali_kelas', compact(
            'kelas',
            'siswa',
            'totalSiswa',
            'totalPelanggaran',
            'siswaBerSp',
        ));
    }
}
