<?php

namespace App\Http\Controllers;

use App\Models\GuruWaliSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class GuruWaliController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();
        $assignedSiswa = Siswa::whereHas('guruWali', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->get();

        $totalSiswa = $assignedSiswa->count();

        // Calculate predicates based on points in current academic year
        $predicateAman = 0;
        $predicateSiswaBaik = 0;
        $predicatePerluPerhatian = 0;
        $predicateDalamPembinaan = 0;
        $predicateKasusBerat = 0;

        foreach ($assignedSiswa as $siswa) {
            $poinTahunIni = $siswa->getTotalPoinTahunIni();
            if ($poinTahunIni == 0) {
                $predicateAman++;
            } elseif ($poinTahunIni >= 1 && $poinTahunIni <= 20) {
                $predicateSiswaBaik++;
            } elseif ($poinTahunIni >= 21 && $poinTahunIni <= 40) {
                $predicatePerluPerhatian++;
            } elseif ($poinTahunIni >= 41 && $poinTahunIni <= 60) {
                $predicateDalamPembinaan++;
            } else {
                $predicateKasusBerat++;
            }
        }

        $recentSiswa = $assignedSiswa->sortByDesc('total_poin')->take(5);

        return view('guru-wali.dashboard', compact('totalSiswa', 'predicateAman', 'predicateSiswaBaik', 'predicatePerluPerhatian', 'predicateDalamPembinaan', 'predicateKasusBerat', 'recentSiswa'));
    }

    public function assignment()
    {
        $userId = Auth::id();
        
        // Mode 1: All Siswa with assignment status
        $allSiswa = Siswa::with(['kelas', 'guruWali.user'])->get();
        
        $myAssignedIds = GuruWaliSiswa::where('id_user', $userId)->pluck('id_siswa')->toArray();

        // Mode 2: Kelas list
        $kelasList = Kelas::orderBy('tingkat')->orderBy('nama_kelas')->get();

        return view('guru-wali.assignment', compact('allSiswa', 'myAssignedIds', 'kelasList', 'userId'));
    }

    public function searchSiswa(Request $request)
    {
        $userId = Auth::id();
        $queryStr = $request->input('q');
        
        $siswa = Siswa::with(['kelas', 'guruWali.user'])
            ->where('nama_siswa', 'like', "%{$queryStr}%")
            ->orWhere('nisn', 'like', "%{$queryStr}%")
            ->limit(50)
            ->get();
            
        $myAssignedIds = GuruWaliSiswa::where('id_user', $userId)->pluck('id_siswa')->toArray();

        $result = $siswa->map(function ($s) use ($myAssignedIds, $userId) {
            $isMine = in_array($s->id_siswa, $myAssignedIds);
            $isAssignedToOther = $s->guruWali && $s->guruWali->id_user != $userId;
            $otherName = $isAssignedToOther ? $s->guruWali->user->nama_lengkap : '';

            return [
                'id_siswa' => $s->id_siswa,
                'nama_siswa' => $s->nama_siswa,
                'nisn' => $s->nisn,
                'nama_kelas' => $s->kelas->nama_kelas ?? '-',
                'total_poin' => $s->total_poin,
                'is_assigned_to_me' => $isMine,
                'is_taken' => $isAssignedToOther,
                'taken_by' => $otherName,
                'id_kelas' => $s->id_kelas,
            ];
        });

        return response()->json($result);
    }

    public function saveAssignment(Request $request)
    {
        $request->validate([
            'id_siswa' => 'nullable|array',
            'id_siswa.*' => 'exists:siswa,id_siswa'
        ]);

        $userId = Auth::id();
        $submittedIds = $request->input('id_siswa', []);

        try {
            DB::beginTransaction();

            // 1. Check for conflicts (students assigned to OTHER guru_wali)
            $conflicts = GuruWaliSiswa::whereIn('id_siswa', $submittedIds)
                                      ->where('id_user', '!=', $userId)
                                      ->with('siswa')
                                      ->get();

            if ($conflicts->isNotEmpty()) {
                $conflictNames = $conflicts->map(function ($gws) {
                    return $gws->siswa->nama_siswa;
                })->implode(', ');

                return back()->with('error', 'Gagal menyimpan. Siswa berikut sudah dipegang oleh guru wali lain: ' . $conflictNames);
            }

            // 2. Delete existing assignments for THIS user
            GuruWaliSiswa::where('id_user', $userId)->delete();

            // 3. Insert new assignments
            $inserts = [];
            foreach ($submittedIds as $siswaId) {
                $inserts[] = [
                    'id_user' => $userId,
                    'id_siswa' => $siswaId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            if (!empty($inserts)) {
                GuruWaliSiswa::insert($inserts);
            }

            DB::commit();

            return redirect()->route('guru_wali.assignment')->with('success', 'Data penugasan siswa berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan sistem saat menyimpan data.');
        }
    }

    public function listSiswa()
    {
        $userId = Auth::id();
        $siswa = Siswa::whereHas('guruWali', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->with('kelas')->orderBy('nama_siswa')->get();

        return view('guru-wali.siswa', compact('siswa'));
    }

    public function detailSiswa($id)
    {
        $userId = Auth::id();
        
        $siswa = Siswa::whereHas('guruWali', function ($q) use ($userId) {
            $q->where('id_user', $userId);
        })->with(['kelas', 'transaksiPelanggaran.jenisPelanggaran', 'logPeringatan'])
        ->where('id_siswa', $id)
        ->firstOrFail();

        return view('guru-wali.detail', compact('siswa'));
    }
}
