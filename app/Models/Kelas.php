<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';

    protected $fillable = [
        'id_user',
        'nama_kelas',
        'tingkat',
    ];

    // Relasi ke User (Wali Kelas)
    public function waliKelas()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    // Relasi ke Siswa
    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'id_kelas', 'id_kelas');
    }
}
