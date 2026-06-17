<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuruWaliSiswa extends Model
{
    use HasFactory;

    protected $table = 'guru_wali_siswa';

    protected $fillable = [
        'id_user',
        'id_siswa',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_siswa');
    }
}
