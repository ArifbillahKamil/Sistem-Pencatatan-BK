<?php

namespace Tests\Feature;

use App\Models\JenisPelanggaran;
use App\Models\Kelas;
use App\Models\LogPeringatan;
use App\Models\Siswa;
use App\Models\TransaksiPelanggaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TransaksiDanKalkulasiTest extends TestCase
{
    use RefreshDatabase;

    private User $guruBk;
    private Kelas $kelas;
    private Siswa $siswa;
    private JenisPelanggaran $jenis10Poin;
    private JenisPelanggaran $jenis25Poin;

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

        $this->siswa = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1111111111',
            'nama_siswa'    => 'Budi Santoso',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
            'total_poin'    => 0,
        ]);

        $this->jenis10Poin = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Terlambat',
            'kategori'         => 'ringan',
            'bobot_poin'       => 10,
            'status'           => 'aktif',
        ]);

        $this->jenis25Poin = JenisPelanggaran::create([
            'nama_pelanggaran' => 'Membolos',
            'kategori'         => 'berat',
            'bobot_poin'       => 25,
            'status'           => 'aktif',
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 14 — Input Transaksi (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_14_guru_bk_berhasil_mencatat_transaksi_pelanggaran()
    {
        $response = $this->actingAs($this->guruBk)->post('/transaksi', [
            'id_siswa'          => $this->siswa->id_siswa,
            'id_jenis'          => $this->jenis10Poin->id_jenis,
            'tanggal_kejadian'  => today()->toDateString(),
            'status_penanganan' => 'belum',
        ]);

        $response->assertRedirect(route('transaksi.index'));
        $this->assertDatabaseHas('transaksi_pelanggaran', [
            'id_siswa' => $this->siswa->id_siswa,
            'id_jenis' => $this->jenis10Poin->id_jenis,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 15 — Input Transaksi (Negatif - siswa tidak dipilih)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_15_transaksi_ditolak_jika_siswa_tidak_dipilih()
    {
        $response = $this->actingAs($this->guruBk)->post('/transaksi', [
            'id_siswa'          => '', // kosong
            'id_jenis'          => $this->jenis10Poin->id_jenis,
            'tanggal_kejadian'  => today()->toDateString(),
            'status_penanganan' => 'belum',
        ]);

        $response->assertSessionHasErrors('id_siswa');
        $this->assertDatabaseCount('transaksi_pelanggaran', 0);
    }

    // ─────────────────────────────────────────────
    // No. 16 — Kalkulasi Poin Otomatis (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_16_poin_siswa_bertambah_setelah_transaksi_disimpan()
    {
        $poinAwal = $this->siswa->total_poin; // 0

        $this->actingAs($this->guruBk)->post('/transaksi', [
            'id_siswa'          => $this->siswa->id_siswa,
            'id_jenis'          => $this->jenis10Poin->id_jenis,
            'tanggal_kejadian'  => today()->toDateString(),
            'status_penanganan' => 'belum',
        ]);

        $this->siswa->refresh();
        $this->assertEquals($poinAwal + 10, $this->siswa->total_poin);
    }

    // ─────────────────────────────────────────────
    // No. 17 — Kalkulasi Poin (Negatif - batalkan transaksi)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_17_poin_tidak_berubah_jika_transaksi_tidak_disimpan()
    {
        $poinAwal = $this->siswa->total_poin; // 0

        // Hanya GET form, tidak POST → tidak ada perubahan
        $this->actingAs($this->guruBk)->get('/transaksi/create');

        $this->siswa->refresh();
        $this->assertEquals($poinAwal, $this->siswa->total_poin);
    }

    // ─────────────────────────────────────────────
    // No. 18 — SP1 Otomatis (Positif - poin >= threshold SP1=25)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_18_sp1_diterbitkan_otomatis_saat_poin_melewati_threshold()
    {
        // Pastikan belum ada SP
        $this->assertDatabaseCount('log_peringatan', 0);

        // Tambah transaksi 25 poin → total 25 = tepat di threshold SP1
        $this->actingAs($this->guruBk)->post('/transaksi', [
            'id_siswa'          => $this->siswa->id_siswa,
            'id_jenis'          => $this->jenis25Poin->id_jenis,
            'tanggal_kejadian'  => today()->toDateString(),
            'status_penanganan' => 'belum',
        ]);

        $this->assertDatabaseHas('log_peringatan', [
            'id_siswa'  => $this->siswa->id_siswa,
            'status_sp' => 'SP1',
            'status'    => 'aktif',
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 19 — SP Tidak Terbit (Negatif - poin < threshold)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_19_sp_tidak_terbit_jika_poin_masih_dibawah_threshold()
    {
        // Transaksi 10 poin → total 10 (SP1 threshold = 25)
        $this->actingAs($this->guruBk)->post('/transaksi', [
            'id_siswa'          => $this->siswa->id_siswa,
            'id_jenis'          => $this->jenis10Poin->id_jenis,
            'tanggal_kejadian'  => today()->toDateString(),
            'status_penanganan' => 'belum',
        ]);

        $this->assertDatabaseCount('log_peringatan', 0);
    }

    // ─────────────────────────────────────────────
    // No. 20 — Lihat Log Peringatan (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_20_guru_bk_dapat_melihat_log_peringatan()
    {
        LogPeringatan::create([
            'id_siswa'           => $this->siswa->id_siswa,
            'status_sp'          => 'SP1',
            'tanggal_terbit'     => today()->toDateString(),
            'total_poin_saat_sp' => 25,
            'status'             => 'aktif',
        ]);

        $response = $this->actingAs($this->guruBk)->get('/log-peringatan');

        $response->assertStatus(200);
        $response->assertSee('SP1');
    }

    // ─────────────────────────────────────────────
    // No. 21 — Log Peringatan Kosong (Positif - tidak ada SP)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_21_halaman_log_peringatan_bisa_diakses_meski_kosong()
    {
        $response = $this->actingAs($this->guruBk)->get('/log-peringatan');

        $response->assertStatus(200);
        $this->assertDatabaseCount('log_peringatan', 0);
    }
}
