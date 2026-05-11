<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransaksiPelanggaran extends Model
{
    protected $table = 'transaksi_pelanggaran';
    protected $primaryKey = 'id_transaksi';

    protected $fillable = [
        'id_siswa',
        'id_jenis',
        'id_user_pelapor',
        'tanggal_kejadian',
        'waktu_kejadian',
        'keterangan',
        'saksi',
        'status_penanganan',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke JenisPelanggaran
    public function jenisPelanggaran()
    {
        return $this->belongsTo(JenisPelanggaran::class, 'id_jenis', 'id_jenis');
    }

    // Relasi ke User (Pelapor)
    public function pelapor()
    {
        return $this->belongsTo(User::class, 'id_user_pelapor');
    }
}
