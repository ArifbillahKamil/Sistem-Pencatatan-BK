<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'role',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function kelas()
    {
        return $this->hasOne(Kelas::class, 'id_user');
    }

    public function transaksiPelanggaran()
    {
        return $this->hasMany(TransaksiPelanggaran::class, 'id_user_pelapor');
    }

    public function guruWaliSiswa()
    {
        return $this->hasMany(GuruWaliSiswa::class, 'id_user');
    }
}
