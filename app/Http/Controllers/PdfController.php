<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use App\Helpers\AcademicYearHelper;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class PdfController extends Controller
{
    public function exportSiswa($id)
    {
        $siswa = Siswa::with([
            'kelas.waliKelas', 
            'transaksiPelanggaran.jenisPelanggaran', 
            'logPeringatan' => function($q) {
                $q->where('status', 'aktif');
            }
        ])->findOrFail($id);

        $dateRange = AcademicYearHelper::getAcademicYearDateRange();
        
        // Filter transactions for current academic year only
        $transaksiTahunIni = $siswa->transaksiPelanggaran->filter(function($t) use ($dateRange) {
            return $t->tanggal_kejadian >= $dateRange['start'] && $t->tanggal_kejadian <= $dateRange['end'];
        })->sortByDesc('tanggal_kejadian');

        $guruBk = User::where('role', 'guru_bk')->first();
        $namaGuruBk = $guruBk ? $guruBk->nama_lengkap : '..........................';
        
        $namaWaliKelas = $siswa->kelas && $siswa->kelas->waliKelas 
            ? $siswa->kelas->waliKelas->nama_lengkap 
            : '..........................';

        $poinTahunIni = $siswa->getTotalPoinTahunIni();
        $predikat = AcademicYearHelper::getPredikat($poinTahunIni);
        
        // Get highest active SP
        $spAktif = $siswa->logPeringatan->sortByDesc(fn($l) => ['SP1'=>1,'SP2'=>2,'SP3'=>3][$l->status_sp] ?? 0)->first();
        $statusSp = $spAktif ? $spAktif->status_sp : 'Tidak Ada';

        $pdf = Pdf::loadView('pdf.rekap_siswa', compact(
            'siswa', 'transaksiTahunIni', 'namaGuruBk', 'namaWaliKelas', 'poinTahunIni', 'predikat', 'statusSp'
        ));

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        $academicYearStr = str_replace('/', '-', AcademicYearHelper::getCurrentAcademicYear());
        $filename = 'Rekap_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $siswa->nama_siswa) . '_' . $academicYearStr . '.pdf';

        return $pdf->download($filename);
    }

    public function exportKelas($id)
    {
        $kelas = Kelas::with(['waliKelas', 'siswa'])->findOrFail($id);
        
        // Get students with their points and predicates
        $siswaList = $kelas->siswa->map(function($s) {
            $poinTahunIni = $s->getTotalPoinTahunIni();
            $predikat = AcademicYearHelper::getPredikat($poinTahunIni);
            $spAktif = $s->logPeringatanAktif()->orderByRaw("CASE status_sp WHEN 'SP3' THEN 1 WHEN 'SP2' THEN 2 WHEN 'SP1' THEN 3 ELSE 4 END")->first();
            $statusSp = $spAktif ? $spAktif->status_sp : 'Tidak Ada';
            
            $s->poin_tahun_ini = $poinTahunIni;
            $s->predikat_label = $predikat['label'];
            $s->status_sp = $statusSp;
            return $s;
        })->sortByDesc('poin_tahun_ini')->values();

        $guruBk = User::where('role', 'guru_bk')->first();
        $namaGuruBk = $guruBk ? $guruBk->nama_lengkap : '..........................';
        $namaWaliKelas = $kelas->waliKelas ? $kelas->waliKelas->nama_lengkap : '..........................';

        $pdf = Pdf::loadView('pdf.rekap_kelas', compact(
            'kelas', 'siswaList', 'namaGuruBk', 'namaWaliKelas'
        ));

        $pdf->setPaper('A4', 'portrait');
        $pdf->setOptions(['defaultFont' => 'sans-serif', 'isRemoteEnabled' => true]);

        $academicYearStr = str_replace('/', '-', AcademicYearHelper::getCurrentAcademicYear());
        $filename = 'Rekap_Kelas_' . preg_replace('/[^A-Za-z0-9\-]/', '_', $kelas->nama_kelas) . '_' . $academicYearStr . '.pdf';

        return $pdf->download($filename);
    }
}
