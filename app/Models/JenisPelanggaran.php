<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisPelanggaran extends Model
{
    protected $table = 'jenis_pelanggaran';
    protected $primaryKey = 'id_jenis';

    protected $fillable = [
        'nama_pelanggaran',
        'deskripsi',
        'bobot_poin',
        'kategori',
        'status',
    ];

    protected $casts = [
        'bobot_poin' => 'integer',
    ];

    // Relasi ke TransaksiPelanggaran
    public function transaksiPelanggaran()
    {
        return $this->hasMany(TransaksiPelanggaran::class, 'id_jenis', 'id_jenis');
    }

    // Scope: hanya yang aktif
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}
