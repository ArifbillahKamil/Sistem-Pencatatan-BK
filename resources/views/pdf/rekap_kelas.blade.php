<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Kelas {{ $kelas->nama_kelas }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .logo { height: 80px; position: absolute; top: 0; left: 0; }
        .school-name { font-size: 18px; font-weight: bold; margin: 0; }
        .doc-title { font-size: 16px; font-weight: bold; margin: 5px 0; }
        .academic-year { font-size: 12px; margin: 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { padding: 3px 0; }
        .info-label { width: 100px; font-weight: bold; }
        
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f8fafc; font-weight: bold; text-align: center; }
        
        .signature-section { position: fixed; bottom: 50px; width: 100%; }
        .signature-table { width: 100%; text-align: center; }
        .signature-table td { width: 33.33%; vertical-align: bottom; height: 100px; }
        
        .footer-date { text-align: right; margin-bottom: 10px; padding-right: 40px; }
    </style>
</head>
<body>
    <div class="header">
        @php
            $logoPath = public_path('images/logo_sekolah.png');
            $logoSrc = file_exists($logoPath) ? $logoPath : '';
        @endphp
        @if($logoSrc)
            <img src="{{ $logoSrc }}" class="logo">
        @else
            <div style="position: absolute; top: 10px; left: 10px; font-weight: bold;">[ LOGO SEKOLAH ]</div>
        @endif
        
        <h1 class="school-name">UPT SMPN 16 GRESIK</h1>
        <h2 class="doc-title">REKAP PELANGGARAN DAN POIN KELAS</h2>
        <p class="academic-year">Tahun Ajaran: {{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama Kelas</td><td>: {{ $kelas->nama_kelas }}</td>
            <td class="info-label">Wali Kelas</td><td>: {{ $namaWaliKelas }}</td>
        </tr>
        <tr>
            <td class="info-label">Tingkat</td><td>: {{ $kelas->tingkat }}</td>
            <td class="info-label">Total Siswa</td><td>: {{ $kelas->siswa->count() }} siswa</td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="35%">Nama Siswa</th>
                <th width="15%">NISN</th>
                <th width="15%">Total Poin</th>
                <th width="20%">Predikat</th>
                <th width="10%">Status SP</th>
            </tr>
        </thead>
        <tbody>
            @forelse($siswaList as $index => $s)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $s->nama_siswa }}</td>
                <td style="text-align: center;">{{ $s->nisn }}</td>
                <td style="text-align: center; font-weight: bold;">{{ $s->poin_tahun_ini }}</td>
                <td style="text-align: center;">{{ $s->predikat_label }}</td>
                <td style="text-align: center;">{{ $s->status_sp }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #666; padding: 20px;">Belum ada data siswa di kelas ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <div class="footer-date">Gresik, {{ date('d F Y') }}</div>
        <table class="signature-table">
            <tr>
                <td>
                    Mengetahui,<br>
                    Guru BK<br><br><br><br><br>
                    ______________________<br>
                    {{ $namaGuruBk }}
                </td>
                <td>
                    <br>
                    Wali Kelas<br><br><br><br><br>
                    ______________________<br>
                    {{ $namaWaliKelas }}
                </td>
                <td>
                    <br>
                    Kepala Sekolah<br><br><br><br><br>
                    ______________________<br>
                    (..............................)
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
