<?php

namespace Tests\Feature;

use App\Models\JenisPelanggaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class JenisPelanggaranTest extends TestCase
{
    use RefreshDatabase;

    private User $guruBk;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guruBk = User::create([
            'username'     => 'gurubk',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru BK Test',
            'role'         => 'guru_bk',
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 11 — Tambah Jenis Pelanggaran (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_11_guru_bk_berhasil_menambah_jenis_pelanggaran()
    {
        $response = $this->actingAs($this->guruBk)->post('/jenis-pelanggaran', [
            'nama_pelanggaran' => 'Membolos',
            'kategori'         => 'berat',
            'bobot_poin'       => 25,
            'status'           => 'aktif',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('jenis_pelanggaran', [
            'nama_pelanggaran' => 'Membolos',
            'bobot_poin'       => 25,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 12 — Tambah Jenis Pelanggaran (Negatif - bobot kosong)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_12_tambah_jenis_pelanggaran_ditolak_jika_bobot_kosong()
    {
        $response = $this->actingAs($this->guruBk)->post('/jenis-pelanggaran', [
            'nama_pelanggaran' => 'Pelanggaran Baru',
            'kategori'         => 'ringan',
            'bobot_poin'       => '', // kosong
            'status'           => 'aktif',
        ]);

        $response->assertSessionHasErrors('bobot_poin');
        $this->assertDatabaseMissing('jenis_pelanggaran', ['nama_pelanggaran' => 'Pelanggaran Baru']);
    }

    // ─────────────────────────────────────────────
    // No. 13 — Hapus Jenis Pelanggaran (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_13_guru_bk_berhasil_menghapus_jenis_pelanggaran()
    {
        $jenis = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Pelanggaran Hapus',
            'kategori'         => 'ringan',
            'bobot_poin'       => 5,
            'status'           => 'aktif',
        ]);

        $response = $this->actingAs($this->guruBk)
            ->delete("/jenis-pelanggaran/{$jenis->id_jenis}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('jenis_pelanggaran', ['id_jenis' => $jenis->id_jenis]);
    }
}
