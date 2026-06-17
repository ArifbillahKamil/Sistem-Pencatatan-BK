<?php

namespace Tests\Feature;

use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    private User $guruBk;
    private Kelas $kelas;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guruBk = User::create([
            'username'     => 'gurubk',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru BK Test',
            'role'         => 'guru_bk',
        ]);

        $this->kelas = Kelas::create([
            'id_user'    => $this->guruBk->id,
            'nama_kelas' => '7A',
            'tingkat'    => 7,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 6 — Tambah Siswa (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_06_guru_bk_berhasil_menambah_siswa_baru()
    {
        $response = $this->actingAs($this->guruBk)->post('/siswa', [
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1234567890',
            'nama_siswa'    => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
            'alamat'        => 'Jl. Test No. 1',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('siswa', [
            'nisn'       => '1234567890',
            'nama_siswa' => 'Budi Santoso',
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 7 — Tambah Siswa (Negatif - NISN duplikat)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_07_tambah_siswa_ditolak_jika_nisn_sudah_digunakan()
    {
        Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1234567890',
            'nama_siswa'    => 'Siswa Lama',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
        ]);

        $response = $this->actingAs($this->guruBk)->post('/siswa', [
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1234567890', // NISN yang sama
            'nama_siswa'    => 'Siswa Baru',
            'jenis_kelamin' => 'P',
        ]);

        $response->assertSessionHasErrors('nisn');
        $this->assertDatabaseMissing('siswa', ['nama_siswa' => 'Siswa Baru']);
    }

    // ─────────────────────────────────────────────
    // No. 8 — Edit Siswa (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_08_guru_bk_berhasil_mengedit_data_siswa()
    {
        $siswa = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '9999999999',
            'nama_siswa'    => 'Nama Lama',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
        ]);

        $response = $this->actingAs($this->guruBk)->put("/siswa/{$siswa->id_siswa}", [
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '9999999999',
            'nama_siswa'    => 'Budi Santoso Revisi',
            'jenis_kelamin' => 'L',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('siswa', ['nama_siswa' => 'Budi Santoso Revisi']);
    }

    // ─────────────────────────────────────────────
    // No. 9 — Hapus Siswa (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_09_guru_bk_berhasil_menghapus_siswa()
    {
        $siswa = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '8888888888',
            'nama_siswa'    => 'Siswa Hapus',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
        ]);

        $response = $this->actingAs($this->guruBk)
            ->delete("/siswa/{$siswa->id_siswa}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('siswa', ['id_siswa' => $siswa->id_siswa]);
    }

    // ─────────────────────────────────────────────
    // No. 10 — Tambah Siswa tanpa data wajib (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_10_tambah_siswa_ditolak_jika_nama_kosong()
    {
        $response = $this->actingAs($this->guruBk)->post('/siswa', [
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '7777777777',
            'nama_siswa'    => '', // kosong
            'jenis_kelamin' => 'L',
        ]);

        $response->assertSessionHasErrors('nama_siswa');
        $this->assertDatabaseCount('siswa', 0);
    }
}
