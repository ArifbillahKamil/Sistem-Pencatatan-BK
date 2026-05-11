<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $primaryKey = 'id_siswa';

    protected $fillable = [
        'id_kelas',
        'nisn',
        'nama_siswa',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'no_telp',
        'total_poin',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'total_poin'    => 'integer',
    ];

    // Relasi ke Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'id_kelas', 'id_kelas');
    }

    // Relasi ke TransaksiPelanggaran
    public function transaksiPelanggaran()
    {
        return $this->hasMany(TransaksiPelanggaran::class, 'id_siswa', 'id_siswa');
    }

    // Relasi ke LogPeringatan
    public function logPeringatan()
    {
        return $this->hasMany(LogPeringatan::class, 'id_siswa', 'id_siswa');
    }

    // Helper: ambil log peringatan yang masih aktif
    public function logPeringatanAktif()
    {
        return $this->hasMany(LogPeringatan::class, 'id_siswa', 'id_siswa')
                    ->where('status', 'aktif');
    }

    // Helper: level SP aktif tertinggi (null | 'SP1' | 'SP2' | 'SP3')
    public function getLevelSpAktifAttribute(): ?string
    {
        $sp = $this->logPeringatanAktif()
                   ->orderByRaw("FIELD(status_sp, 'SP3', 'SP2', 'SP1')")
                   ->first();

        return $sp?->status_sp;
    }
}
