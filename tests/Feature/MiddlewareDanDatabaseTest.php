<?php

namespace Tests\Feature;

use App\Models\GuruWaliSiswa;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\QueryException;
use Tests\TestCase;

class MiddlewareDanDatabaseTest extends TestCase
{
    use RefreshDatabase;

    private User $guruBk;
    private User $waliKelas;
    private User $guruWali;
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

        $this->guruWali = User::create([
            'username'     => 'guruwali',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru Wali Test',
            'role'         => 'guru_wali',
        ]);

        $this->kelas = Kelas::create([
            'id_user'    => $this->guruBk->id,
            'nama_kelas' => '7A',
            'tingkat'    => 7,
        ]);

        $this->siswa = Siswa::create([
            'id_kelas'      => $this->kelas->id_kelas,
            'nisn'          => '1111111111',
            'nama_siswa'    => 'Siswa Test',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2010-01-01',
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 33 — Middleware: Wali Kelas Diblokir ke Route Guru BK (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_33_wali_kelas_diblokir_mengakses_route_khusus_guru_bk()
    {
        $response = $this->actingAs($this->waliKelas)
            ->get('/jenis-pelanggaran/create');

        $response->assertForbidden();
    }

    /** @test */
    public function test_33b_guru_wali_diblokir_mengakses_route_khusus_guru_bk()
    {
        $response = $this->actingAs($this->guruWali)
            ->get('/jenis-pelanggaran/create');

        $response->assertForbidden();
    }

    /** @test */
    public function test_33c_guru_bk_diblokir_mengakses_route_khusus_wali_kelas()
    {
        $response = $this->actingAs($this->guruBk)
            ->get('/wali/siswa');

        $response->assertForbidden();
    }

    /** @test */
    public function test_33d_guru_bk_diblokir_mengakses_route_khusus_guru_wali()
    {
        $response = $this->actingAs($this->guruBk)
            ->get('/guru-wali/siswa');

        $response->assertForbidden();
    }

    // ─────────────────────────────────────────────
    // No. 34 — Middleware: Pengguna Tidak Login Diblokir (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_34_pengguna_tidak_login_diredirect_ke_halaman_login()
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /** @test */
    public function test_34b_pengguna_tidak_login_tidak_bisa_akses_route_apapun()
    {
        $routes = [
            '/siswa',
            '/jenis-pelanggaran',
            '/transaksi',
            '/log-peringatan',
            '/wali/siswa',
            '/guru-wali/siswa',
        ];

        foreach ($routes as $route) {
            $response = $this->get($route);
            $this->assertTrue(
                $response->isRedirect(),
                "Route {$route} seharusnya redirect ke login untuk pengguna yang belum login."
            );
        }
    }

    // ─────────────────────────────────────────────
    // No. 35 — Database: Duplikat Siswa ke Guru Wali Lain Dicegah (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_35_sistem_mencegah_satu_siswa_ditugaskan_ke_lebih_dari_satu_guru_wali()
    {
        $guruWaliA = User::create([
            'username'     => 'guruwali_a',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru Wali A',
            'role'         => 'guru_wali',
        ]);

        $guruWaliB = User::create([
            'username'     => 'guruwali_b',
            'password'     => bcrypt('password'),
            'nama_lengkap' => 'Guru Wali B',
            'role'         => 'guru_wali',
        ]);

        // Tugaskan siswa ke guruWaliA
        GuruWaliSiswa::create([
            'id_user'  => $guruWaliA->id,
            'id_siswa' => $this->siswa->id_siswa,
        ]);

        // guruWaliB coba tugaskan siswa yang sama → harus gagal karena UNIQUE(id_siswa)
        $this->expectException(QueryException::class);

        GuruWaliSiswa::create([
            'id_user'  => $guruWaliB->id,
            'id_siswa' => $this->siswa->id_siswa,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 36 — Database: Insert Duplikat oleh Guru Wali Sendiri Dicegah (Negatif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_36_guru_wali_tidak_bisa_insert_duplikat_siswa_yang_sudah_ada_di_daftarnya()
    {
        // Siswa sudah ditugaskan ke guruWali
        GuruWaliSiswa::create([
            'id_user'  => $this->guruWali->id,
            'id_siswa' => $this->siswa->id_siswa,
        ]);

        // Coba insert lagi siswa yang sama → harus gagal
        $this->expectException(QueryException::class);

        GuruWaliSiswa::create([
            'id_user'  => $this->guruWali->id,
            'id_siswa' => $this->siswa->id_siswa,
        ]);
    }
}
