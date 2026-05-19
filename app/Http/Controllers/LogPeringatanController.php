<?php

namespace App\Http\Controllers;

use App\Models\LogPeringatan;
use App\Models\Siswa;
use Illuminate\Http\Request;

class LogPeringatanController extends Controller
{
    public function index(Request $request)
    {
        $query = LogPeringatan::with(['siswa.kelas'])
            ->latest('tanggal_terbit')
            ->latest('id_log');

        if ($request->filled('status_sp')) {
            $query->where('status_sp', $request->status_sp);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('siswa', fn($q) =>
                $q->where('nama_siswa', 'like', "%{$search}%")
                  ->orWhere('nisn', 'like', "%{$search}%")
            );
        }

        $logs = $query->paginate(20)->withQueryString();

        // Counts for summary cards
        $countSp1 = LogPeringatan::where('status_sp', 'SP1')->where('status', 'aktif')->count();
        $countSp2 = LogPeringatan::where('status_sp', 'SP2')->where('status', 'aktif')->count();
        $countSp3 = LogPeringatan::where('status_sp', 'SP3')->where('status', 'aktif')->count();

        return view('log-peringatan.index', compact('logs', 'countSp1', 'countSp2', 'countSp3'));
    }

    public function show(Siswa $siswa)
    {
        $siswa->load(['kelas', 'logPeringatan' => fn($q) => $q->latest('tanggal_terbit')]);
        return view('log-peringatan.show', compact('siswa'));
    }

    /**
     * Toggle SP status: aktif ↔ selesai (manual override)
     */
    public function toggleStatus(LogPeringatan $log)
    {
        $log->update([
            'status' => $log->status === 'aktif' ? 'selesai' : 'aktif',
        ]);

        $label = $log->status === 'aktif' ? 'diaktifkan kembali' : 'ditandai selesai';
        return back()->with('success', "{$log->status_sp} berhasil {$label}.");
    }
}
