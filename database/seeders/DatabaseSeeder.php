<?php

namespace Database\Seeders;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ─── 1. Guru BK ───────────────────────────────────────────────
        $gurubk = User::create([
            'username'     => 'gurubk',
            'password'     => Hash::make('password'),
            'nama_lengkap' => 'Ibu Siti Rahayu, S.Pd.',
            'role'         => 'guru_bk',
        ]);

        // ─── 2. Wali Kelas ────────────────────────────────────────────
        $walikelas = User::create([
            'username'     => 'walikelas',
            'password'     => Hash::make('password'),
            'nama_lengkap' => 'Bapak Andi Prasetyo, S.Pd.',
            'role'         => 'wali_kelas',
        ]);

        // ─── 2.5 Guru Wali ────────────────────────────────────────────
        $guruwali = User::create([
            'username'     => 'guruwali',
            'password'     => Hash::make('password'),
            'nama_lengkap' => 'Guru Wali 1',
            'role'         => 'guru_wali',
        ]);

        // ─── 3. Kelas (linked to wali_kelas) ─────────────────────────
        $kelas = Kelas::create([
            'id_user'    => $walikelas->id,
            'nama_kelas' => '9A',
            'tingkat'    => 9,
        ]);

        // ─── 4. Siswa (5 records in that kelas) ──────────────────────
        $siswaData = [
            [
                'nisn'          => '0123456789',
                'nama_siswa'    => 'Ahmad Fauzi',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-03-15',
                'alamat'        => 'Jl. Veteran No. 12, Gresik',
                'no_telp'       => '08123456781',
                'total_poin'    => 0,
            ],
            [
                'nisn'          => '0123456790',
                'nama_siswa'    => 'Siti Nurhaliza',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-07-22',
                'alamat'        => 'Jl. Pahlawan No. 5, Gresik',
                'no_telp'       => '08123456782',
                'total_poin'    => 0,
            ],
            [
                'nisn'          => '0123456791',
                'nama_siswa'    => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-11-08',
                'alamat'        => 'Jl. Diponegoro No. 3, Gresik',
                'no_telp'       => '08123456783',
                'total_poin'    => 0,
            ],
            [
                'nisn'          => '0123456792',
                'nama_siswa'    => 'Dewi Anggraeni',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-01-30',
                'alamat'        => 'Jl. Gajah Mada No. 8, Gresik',
                'no_telp'       => '08123456784',
                'total_poin'    => 0,
            ],
            [
                'nisn'          => '0123456793',
                'nama_siswa'    => 'Rizky Hidayat',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-05-17',
                'alamat'        => 'Jl. Ahmad Yani No. 20, Gresik',
                'no_telp'       => '08123456785',
                'total_poin'    => 0,
            ],
        ];

        foreach ($siswaData as $data) {
            Siswa::create(array_merge($data, ['id_kelas' => $kelas->id_kelas]));
        }

        // ─── 5. Jenis Pelanggaran (5 records: mix of ringan/sedang/berat) ─
        $jenisPelanggaran = [
            [
                'nama_pelanggaran' => 'Terlambat masuk sekolah',
                'deskripsi'        => 'Siswa datang setelah bel masuk berbunyi tanpa keterangan yang jelas.',
                'bobot_poin'       => 5,
                'kategori'         => 'ringan',
                'status'           => 'aktif',
            ],
            [
                'nama_pelanggaran' => 'Tidak memakai seragam lengkap',
                'deskripsi'        => 'Siswa tidak memakai atribut seragam sekolah sesuai ketentuan.',
                'bobot_poin'       => 10,
                'kategori'         => 'ringan',
                'status'           => 'aktif',
            ],
            [
                'nama_pelanggaran' => 'Berkelahi dengan teman',
                'deskripsi'        => 'Siswa terlibat perkelahian fisik di lingkungan sekolah.',
                'bobot_poin'       => 30,
                'kategori'         => 'berat',
                'status'           => 'aktif',
            ],
            [
                'nama_pelanggaran' => 'Membolos pelajaran',
                'deskripsi'        => 'Siswa meninggalkan kelas tanpa izin dari guru yang bersangkutan.',
                'bobot_poin'       => 15,
                'kategori'         => 'sedang',
                'status'           => 'aktif',
            ],
            [
                'nama_pelanggaran' => 'Membawa/menggunakan HP saat KBM',
                'deskripsi'        => 'Siswa menggunakan handphone saat kegiatan belajar mengajar berlangsung.',
                'bobot_poin'       => 20,
                'kategori'         => 'sedang',
                'status'           => 'aktif',
            ],
        ];

        foreach ($jenisPelanggaran as $jenis) {
            JenisPelanggaran::create($jenis);
        }
    }
}
