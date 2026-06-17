<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Rekap Siswa</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #333; line-height: 1.5; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .logo { height: 80px; position: absolute; top: 0; left: 0; }
        .school-name { font-size: 18px; font-weight: bold; margin: 0; }
        .doc-title { font-size: 16px; font-weight: bold; margin: 5px 0; }
        .academic-year { font-size: 12px; margin: 0; }
        
        .info-table { width: 100%; margin-bottom: 20px; }
        .info-table td { vertical-align: top; padding: 3px 0; }
        .info-label { width: 120px; font-weight: bold; }
        
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background-color: #f8fafc; font-weight: bold; text-align: left; }
        
        .signature-section { position: fixed; bottom: 50px; width: 100%; }
        .signature-table { width: 100%; text-align: center; }
        .signature-table td { width: 33.33%; vertical-align: bottom; height: 100px; }
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
        <h2 class="doc-title">REKAP PELANGGARAN DAN POIN SISWA</h2>
        <p class="academic-year">Tahun Ajaran: {{ \App\Helpers\AcademicYearHelper::getCurrentAcademicYear() }}</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="50%">
                <table width="100%">
                    <tr><td class="info-label">Nama Siswa</td><td>: {{ $siswa->nama_siswa }}</td></tr>
                    <tr><td class="info-label">NISN</td><td>: {{ $siswa->nisn }}</td></tr>
                    <tr><td class="info-label">Kelas</td><td>: {{ $siswa->kelas->nama_kelas ?? '-' }}</td></tr>
                    <tr><td class="info-label">Jenis Kelamin</td><td>: {{ $siswa->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td></tr>
                </table>
            </td>
            <td width="50%">
                <table width="100%">
                    <tr>
                        <td class="info-label">Total Poin</td>
                        <td style="font-weight: bold; font-size: 14px;">: {{ $poinTahunIni }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Predikat</td>
                        <td style="font-weight: bold;">: {{ $predikat['label'] }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Status SP Aktif</td>
                        <td style="font-weight: bold;">: {{ $statusSp }}</td>
                    </tr>
                    <tr>
                        <td class="info-label">Wali Kelas</td>
                        <td>: {{ $namaWaliKelas }}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="15%">Tanggal</th>
                <th width="35%">Jenis Pelanggaran</th>
                <th width="15%">Kategori</th>
                <th width="10%">Bobot</th>
                <th width="20%">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transaksiTahunIni as $index => $t)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $t->tanggal_kejadian->format('d/m/Y') }}</td>
                <td>{{ $t->jenisPelanggaran->nama_pelanggaran ?? '-' }}</td>
                <td>{{ ucfirst($t->jenisPelanggaran->kategori ?? '-') }}</td>
                <td style="text-align: center;">+{{ $t->jenisPelanggaran->bobot_poin ?? 0 }}</td>
                <td>{{ $t->keterangan ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #666; padding: 20px;">Tidak ada pelanggaran pada tahun ajaran ini</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="signature-section">
        <table class="signature-table">
            <tr>
                <td>
                    Guru BK<br><br><br><br><br>
                    ______________________<br>
                    {{ $namaGuruBk }}
                </td>
                <td>
                    Wali Kelas<br><br><br><br><br>
                    ______________________<br>
                    {{ $namaWaliKelas }}
                </td>
                <td>
                    Siswa / Wali Murid<br><br><br><br><br>
                    ______________________<br>
                    {{ $siswa->nama_siswa }}
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
