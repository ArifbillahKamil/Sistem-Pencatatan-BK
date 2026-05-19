<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\LogPeringatan;
use App\Models\TransaksiPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WaliKelasController extends Controller
{
    /**
     * Get the kelas owned by the logged-in wali kelas.
     */
    private function getKelas(): ?Kelas
    {
        return Kelas::with('siswa')->where('id_user', Auth::id())->first();
    }

    public function siswa()
    {
        $kelas = $this->getKelas();
        $siswa = $kelas?->siswa()->orderBy('nama_siswa')->get() ?? collect();

        return view('wali.siswa', compact('kelas', 'siswa'));
    }

    public function pelanggaran(Request $request)
    {
        $kelas = $this->getKelas();

        if (! $kelas) {
            return view('wali.pelanggaran', ['kelas' => null, 'transaksi' => collect()]);
        }

        $siswaIds = $kelas->siswa()->pluck('id_siswa');

        $query = TransaksiPelanggaran::with(['siswa', 'jenisPelanggaran'])
            ->whereIn('id_siswa', $siswaIds)
            ->latest('tanggal_kejadian');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama_siswa', 'like', "%{$search}%")
            );
        }

        $transaksi = $query->paginate(15)->withQueryString();

        return view('wali.pelanggaran', compact('kelas', 'transaksi'));
    }

    public function sp(Request $request)
    {
        $kelas = $this->getKelas();

        if (! $kelas) {
            return view('wali.sp', ['kelas' => null, 'logs' => collect()]);
        }

        $siswaIds = $kelas->siswa()->pluck('id_siswa');

        $query = LogPeringatan::with(['siswa'])
            ->whereIn('id_siswa', $siswaIds)
            ->latest('tanggal_terbit');

        if ($request->filled('status_sp')) {
            $query->where('status_sp', $request->status_sp);
        }

        $logs = $query->get();

        return view('wali.sp', compact('kelas', 'logs'));
    }
}
