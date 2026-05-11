<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogPeringatan extends Model
{
    protected $table = 'log_peringatan';
    protected $primaryKey = 'id_log';

    protected $fillable = [
        'id_siswa',
        'status_sp',
        'tanggal_terbit',
        'keterangan_sp',
        'total_poin_saat_sp',
        'status',
    ];

    protected $casts = [
        'tanggal_terbit'    => 'date',
        'total_poin_saat_sp' => 'integer',
    ];

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }

    // Scope: hanya yang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
