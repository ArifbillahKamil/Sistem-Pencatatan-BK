<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaliKelasTest extends TestCase
{
    use RefreshDatabase;

    private User $guruBk;
    private User $waliKelas;
    private Kelas $kelas;
    private Siswa $siswa;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guruBk = User::create([
            'username'     => 'gurubk',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru BK Test',
            'role'         => 'guru_bk',
        ]);

        $this->waliKelas = User::create([
            'username'     => 'walikelas',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Wali Kelas Test',
            'role'         => 'wali_kelas',
        ]);

        $this->kelas = Kelas::create([
            'id_user'    => $this->waliKelas->id,
            'nama_kelas' => '7A',
            'tingkat'    => 7,
        ]);

        $this->siswa = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1111111111',
            'nama_siswa'    => 'Siswa Kelas 7A',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
            'total_poin'    => 5,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 22 — Wali Kelas Lihat Daftar Siswa (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_22_wali_kelas_dapat_melihat_daftar_siswa_kelasnya()
    {
        $response = $this->actingAs($this->waliKelas)->get('/wali/siswa');

        $response->assertStatus(200);
        $response->assertSee('Siswa Kelas 7A');
    }

    // ─────────────────────────────────────────────
    // No. 23 — Wali Kelas Tidak Lihat Data Kelas Lain (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_23_wali_kelas_tidak_bisa_akses_data_diluar_kelasnya()
    {
        // Buat kelas lain dengan siswa berbeda
        $waliLain = User::create([
            'username'     => 'wali_lain',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Wali Lain',
            'role'         => 'wali_kelas',
        ]);

        $kelasLain = Kelas::create([
            'id_user'    => $waliLain->id,
            'nama_kelas' => '8B',
            'tingkat'    => 8,
        ]);

        Siswa::create([
            'id_kelas'      => $kelasLain->id_kelas,
            'nisn'          => '9999999999',
            'nama_siswa'    => 'Siswa Kelas 8B',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2010-06-01',
        ]);

        // Wali kelas 7A tidak seharusnya melihat data 8B
        $response = $this->actingAs($this->waliKelas)->get('/wali/siswa');

        $response->assertStatus(200);
        $response->assertDontSee('Siswa Kelas 8B');
    }

    // ─────────────────────────────────────────────
    // No. 24 — Wali Kelas Lihat Riwayat Pelanggaran (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_24_wali_kelas_dapat_melihat_riwayat_pelanggaran_kelasnya()
    {
        $response = $this->actingAs($this->waliKelas)->get('/wali/pelanggaran');

        $response->assertStatus(200);
    }

    // ─────────────────────────────────────────────
    // No. 25 — Wali Kelas Tidak Ada Tombol Edit (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_25_halaman_wali_kelas_tidak_punya_akses_route_crud()
    {
        // Wali kelas tidak memiliki route untuk POST siswa
        $response = $this->actingAs($this->waliKelas)->post('/siswa', [
            'nama_siswa' => 'Siswa Baru dari Wali',
        ]);

        // Harus 403 Forbidden
        $response->assertForbidden();
    }

    // ─────────────────────────────────────────────
    // No. 26 — Wali Kelas Akses Route Edit Langsung (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_26_wali_kelas_diblokir_akses_route_edit_siswa_langsung()
    {
        $response = $this->actingAs($this->waliKelas)
            ->get("/siswa/{$this->siswa->id_siswa}/edit");

        $response->assertForbidden();
    }
}
