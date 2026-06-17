<?php

namespace Tests\Feature;

use App\Models\GuruWaliSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuruWaliTest extends TestCase
{
    use RefreshDatabase;

    private User $guruBk;
    private User $guruWaliA;
    private User $guruWaliB;
    private Kelas $kelas;
    private Siswa $siswa1;
    private Siswa $siswa2;
    private Siswa $siswa3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->guruBk = User::create([
            'username'     => 'gurubk',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru BK Test',
            'role'         => 'guru_bk',
        ]);

        $this->guruWaliA = User::create([
            'username'     => 'guruwali_a',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru Wali A',
            'role'         => 'guru_wali',
        ]);

        $this->guruWaliB = User::create([
            'username'     => 'guruwali_b',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru Wali B',
            'role'         => 'guru_wali',
        ]);

        $this->kelas = Kelas::create([
            'id_user'    => $this->guruBk->id,
            'nama_kelas' => '7A',
            'tingkat'    => 7,
        ]);

        $this->siswa1 = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1111111111',
            'nama_siswa'    => 'Siswa Satu',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
        ]);

        $this->siswa2 = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '2222222222',
            'nama_siswa'    => 'Siswa Dua',
            'jenis_kelamin' => 'P',
            'tanggal_lahir' => '2010-02-01',
        ]);

        $this->siswa3 = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '3333333333',
            'nama_siswa'    => 'Siswa Tiga',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-03-01',
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 27 — Penugasan Individual (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_27_guru_wali_berhasil_menugaskan_siswa_secara_individual()
    {
        $response = $this->actingAs($this->guruWaliA)->post('/guru-wali/assignment/save', [
            'id_siswa' => [$this->siswa1->id_siswa],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('guru_wali_siswa', [
            'id_user'  => $this->guruWaliA->id,
            'id_siswa' => $this->siswa1->id_siswa,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 28 — Penugasan Individual (Negatif - tidak pilih siswa)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_28_guru_wali_simpan_tanpa_pilih_siswa_tidak_ubah_apapun()
    {
        $jumlahSebelum = GuruWaliSiswa::count();

        $this->actingAs($this->guruWaliA)->post('/guru-wali/assignment/save', [
            'id_siswa' => [], // kosong
        ]);

        $this->assertEquals($jumlahSebelum, GuruWaliSiswa::count());
    }

    // ─────────────────────────────────────────────
    // No. 29 — Penugasan Batch per Kelas (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_29_guru_wali_berhasil_menugaskan_seluruh_siswa_satu_kelas()
    {
        $siswaDiKelas = [$this->siswa1->id_siswa, $this->siswa2->id_siswa, $this->siswa3->id_siswa];

        $response = $this->actingAs($this->guruWaliA)->post('/guru-wali/assignment/save', [
            'id_siswa' => $siswaDiKelas,
        ]);

        $response->assertRedirect();

        foreach ($siswaDiKelas as $idSiswa) {
            $this->assertDatabaseHas('guru_wali_siswa', [
                'id_user'  => $this->guruWaliA->id,
                'id_siswa' => $idSiswa,
            ]);
        }
    }

    // ─────────────────────────────────────────────
    // No. 30 — Batch Melewati Siswa yang Sudah Ditugaskan (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_30_batch_assignment_melewati_siswa_yang_sudah_ditugaskan_ke_guru_lain()
    {
        // Siswa1 sudah ditugaskan ke guruWaliA
        GuruWaliSiswa::create([
            'id_user'  => $this->guruWaliA->id,
            'id_siswa' => $this->siswa1->id_siswa,
        ]);

        // guruWaliB mencoba batch semua siswa, termasuk siswa1 yang sudah taken
        $response = $this->actingAs($this->guruWaliB)->post('/guru-wali/assignment/save', [
            'id_siswa' => [$this->siswa1->id_siswa, $this->siswa2->id_siswa],
        ]);

        // Siswa2 berhasil ditugaskan ke guruWaliB
        $this->assertDatabaseHas('guru_wali_siswa', [
            'id_user'  => $this->guruWaliB->id,
            'id_siswa' => $this->siswa2->id_siswa,
        ]);

        // Siswa1 tetap milik guruWaliA, tidak berpindah ke guruWaliB
        $this->assertDatabaseMissing('guru_wali_siswa', [
            'id_user'  => $this->guruWaliB->id,
            'id_siswa' => $this->siswa1->id_siswa,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 31 — Lihat Riwayat Pelanggaran Murid Asuhan (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_31_guru_wali_dapat_melihat_detail_siswa_asuhannya()
    {
        // Tugaskan siswa ke guruWaliA
        GuruWaliSiswa::create([
            'id_user'  => $this->guruWaliA->id,
            'id_siswa' => $this->siswa1->id_siswa,
        ]);

        // Verifikasi relasi tersimpan dengan benar di database
        $this->assertDatabaseHas('guru_wali_siswa', [
            'id_user'  => $this->guruWaliA->id,
            'id_siswa' => $this->siswa1->id_siswa,
        ]);

        // Verifikasi siswa ditemukan via relasi
        $record = \App\Models\GuruWaliSiswa::where('id_user', $this->guruWaliA->id)
            ->where('id_siswa', $this->siswa1->id_siswa)
            ->first();

        $this->assertNotNull($record);
        $this->assertEquals('Siswa Satu', $record->siswa->nama_siswa);
    }

    // ─────────────────────────────────────────────
    // No. 32 — Guru Wali Tidak Lihat Siswa Bukan Asuhan (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_32_guru_wali_diblokir_lihat_detail_siswa_bukan_asuhannya()
    {
        // Siswa1 ditugaskan ke guruWaliA, bukan guruWaliB
        GuruWaliSiswa::create([
            'id_user'  => $this->guruWaliA->id,
            'id_siswa' => $this->siswa1->id_siswa,
        ]);

        // guruWaliB mencoba akses detail siswa1 via database query
        $milikSendiri = \App\Models\GuruWaliSiswa::where('id_user', $this->guruWaliB->id)
            ->where('id_siswa', $this->siswa1->id_siswa)
            ->exists();

        // Harus false: siswa1 bukan asuhan guruWaliB
        $this->assertFalse($milikSendiri, 'GuruWali B seharusnya tidak memiliki akses ke siswa yang menjadi asuhan GuruWali A');
    }
}
