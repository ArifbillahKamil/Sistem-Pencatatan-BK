<?php

namespace App\Http\Controllers;

use App\Models\JenisPelanggaran;
use App\Models\LogPeringatan;
use App\Models\Siswa;
use App\Models\TransaksiPelanggaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransaksiPelanggaranController extends Controller
{
    // SP Thresholds
    const SP1_THRESHOLD = 25;
    const SP2_THRESHOLD = 50;
    const SP3_THRESHOLD = 75;

    public function index(Request $request)
    {
        $query = TransaksiPelanggaran::with(['siswa.kelas', 'jenisPelanggaran', 'pelapor'])
            ->latest('tanggal_kejadian')
            ->latest('id_transaksi');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
            );
        }

        if ($request->filled('kategori')) {
            $query->whereHas('jenisPelanggaran', fn($q) =>
                $q->where('kategori', $request->kategori)
            );
        }

        if ($request->filled('status')) {
            $query->where('status_penanganan', $request->status);
        }

        $transaksi = $query->paginate(15)->withQueryString();

        return view('transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $siswaList  = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $jenisList  = JenisPelanggaran::where('status', 'aktif')
                                       ->orderBy('kategori')
                                       ->orderBy('nama_pelanggaran')
                                       ->get();

        return view('transaksi.create', compact('siswaList', 'jenisList'));
    }

    public function searchSiswa(Request $request)
    {
        $queryStr = $request->input('q');
        $siswa = Siswa::with('kelas')
            ->where('nama_siswa', 'like', "%{$queryStr}%")
            ->orWhere('nisn', 'like', "%{$queryStr}%")
            ->limit(20)
            ->get()
            ->map(function($s) {
                return [
                    'id_siswa' => $s->id_siswa,
                    'nama_siswa' => $s->nama_siswa,
                    'nisn' => $s->nisn,
                    'nama_kelas' => $s->kelas->nama_kelas ?? '-',
                    'total_poin' => $s->total_poin,
                ];
            });

        return response()->json($siswa);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_siswa'          => 'required|exists:siswa,id_siswa',
            'id_jenis'          => 'required|exists:jenis_pelanggaran,id_jenis',
            'tanggal_kejadian'  => 'required|date|before_or_equal:today',
            'waktu_kejadian'    => 'nullable|date_format:H:i',
            'keterangan'        => 'nullable|string|max:500',
            'saksi'             => 'nullable|string|max:100',
            'status_penanganan' => 'required|in:belum,proses,selesai',
        ], [
            'id_siswa.required'         => 'Siswa wajib dipilih.',
            'id_jenis.required'         => 'Jenis pelanggaran wajib dipilih.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
        ]);

        DB::transaction(function () use ($validated) {
            // 1. Simpan transaksi
            TransaksiPelanggaran::create(array_merge($validated, [
                'id_user_pelapor' => Auth::id(),
            ]));

            // 2. Recalculate & update total_poin siswa
            $siswa = Siswa::find($validated['id_siswa']);
            $this->recalculatePoin($siswa);

            // 3. Auto-generate SP jika threshold terlewati
            $this->checkAndGenerateSp($siswa);
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Pelanggaran berhasil dicatat.');
    }

    public function show(TransaksiPelanggaran $transaksi)
    {
        $transaksi->load(['siswa.kelas', 'jenisPelanggaran', 'pelapor']);
        return view('transaksi.show', compact('transaksi'));
    }

    public function edit(TransaksiPelanggaran $transaksi)
    {
        $siswaList  = Siswa::with('kelas')->orderBy('nama_siswa')->get();
        $jenisList  = JenisPelanggaran::where('status', 'aktif')
                                       ->orderBy('kategori')
                                       ->orderBy('nama_pelanggaran')
                                       ->get();

        return view('transaksi.edit', compact('transaksi', 'siswaList', 'jenisList'));
    }

    public function update(Request $request, TransaksiPelanggaran $transaksi)
    {
        $validated = $request->validate([
            'id_siswa'          => 'required|exists:siswa,id_siswa',
            'id_jenis'          => 'required|exists:jenis_pelanggaran,id_jenis',
            'tanggal_kejadian'  => 'required|date|before_or_equal:today',
            'waktu_kejadian'    => 'nullable|date_format:H:i',
            'keterangan'        => 'nullable|string|max:500',
            'saksi'             => 'nullable|string|max:100',
            'status_penanganan' => 'required|in:belum,proses,selesai',
        ], [
            'id_siswa.required'         => 'Siswa wajib dipilih.',
            'id_jenis.required'         => 'Jenis pelanggaran wajib dipilih.',
            'tanggal_kejadian.required' => 'Tanggal kejadian wajib diisi.',
            'tanggal_kejadian.before_or_equal' => 'Tanggal tidak boleh melebihi hari ini.',
        ]);

        $oldSiswaId = $transaksi->id_siswa;

        DB::transaction(function () use ($validated, $transaksi, $oldSiswaId) {
            $transaksi->update($validated);

            // Recalculate poin for the old siswa (if changed)
            if ($oldSiswaId != $validated['id_siswa']) {
                $oldSiswa = Siswa::find($oldSiswaId);
                $this->recalculatePoin($oldSiswa);
                $this->checkAndGenerateSp($oldSiswa);
            }

            // Recalculate poin for the current (new) siswa
            $siswa = Siswa::find($validated['id_siswa']);
            $this->recalculatePoin($siswa);
            $this->checkAndGenerateSp($siswa);
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Data pelanggaran berhasil diperbarui.');
    }

    public function destroy(TransaksiPelanggaran $transaksi)
    {
        $siswaId = $transaksi->id_siswa;

        DB::transaction(function () use ($transaksi, $siswaId) {
            $transaksi->delete();

            $siswa = Siswa::find($siswaId);
            $this->recalculatePoin($siswa);
            $this->checkAndGenerateSp($siswa);
        });

        return redirect()->route('transaksi.index')
            ->with('success', 'Catatan pelanggaran berhasil dihapus dan poin siswa telah diperbarui.');
    }

    // ─── Helpers ─────────────────────────────────────────────────────

    /**
     * Recalculate siswa.total_poin from all their transaksi.
     */
    private function recalculatePoin(Siswa $siswa): void
    {
        $totalPoin = $siswa->transaksiPelanggaran()
            ->join('jenis_pelanggaran', 'transaksi_pelanggaran.id_jenis', '=', 'jenis_pelanggaran.id_jenis')
            ->sum('jenis_pelanggaran.bobot_poin');

        $siswa->update(['total_poin' => (int) $totalPoin]);
    }

    /**
     * Check poin thresholds and auto-generate SP log if needed.
     * Each level (SP1, SP2, SP3) is generated only once (first crossing).
     */
    private function checkAndGenerateSp(Siswa $siswa): void
    {
        $siswa->refresh();
        $poin = $siswa->total_poin;

        $thresholds = [
            'SP1' => self::SP1_THRESHOLD,
            'SP2' => self::SP2_THRESHOLD,
            'SP3' => self::SP3_THRESHOLD,
        ];

        foreach ($thresholds as $level => $threshold) {
            $alreadyExists = LogPeringatan::where('id_siswa', $siswa->id_siswa)
                                          ->where('status_sp', $level)
                                          ->exists();

            if ($poin >= $threshold && ! $alreadyExists) {
                LogPeringatan::create([
                    'id_siswa'          => $siswa->id_siswa,
                    'status_sp'         => $level,
                    'tanggal_terbit'    => now()->toDateString(),
                    'total_poin_saat_sp' => $poin,
                    'status'            => 'aktif',
                    'keterangan_sp'     => "Diterbitkan otomatis saat total poin mencapai {$poin} (threshold {$level}: {$threshold} poin).",
                ]);
            }

            // If poin drops below threshold, mark the existing SP as selesai
            if ($poin < $threshold && $alreadyExists) {
                LogPeringatan::where('id_siswa', $siswa->id_siswa)
                              ->where('status_sp', $level)
                              ->where('status', 'aktif')
                              ->update(['status' => 'selesai']);
            }
        }
    }
}
