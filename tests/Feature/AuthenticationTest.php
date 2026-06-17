<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    // ─────────────────────────────────────────────
    // Helpers
    // ─────────────────────────────────────────────

    private function makeUser(string $role, ?string $username = null): User
    {
        return User::create([
            'username'    => $username ?? $role . '_user',
            'password'    => bcrypt('password'),
            'nama_lengkap' => 'Test ' . ucfirst($role),
            'role'        => $role,
        ]);
    }

    // ─────────────────────────────────────────────
    // No. 1 — Login Guru BK (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_01_guru_bk_dapat_login_dengan_kredensial_valid()
    {
        $this->makeUser('guru_bk', 'gurubk');

        $response = $this->post('/login', [
            'username' => 'gurubk',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    // ─────────────────────────────────────────────
    // No. 2 — Login Guru BK (Negatif - password salah)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_02_guru_bk_ditolak_dengan_password_salah()
    {
        $this->makeUser('guru_bk', 'gurubk');

        $response = $this->post('/login', [
            'username' => 'gurubk',
            'password' => 'salah123',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    // ─────────────────────────────────────────────
    // No. 3 — Login Wali Kelas (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_03_wali_kelas_dapat_login_dengan_kredensial_valid()
    {
        $this->makeUser('wali_kelas', 'walikelas');

        $response = $this->post('/login', [
            'username' => 'walikelas',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    // ─────────────────────────────────────────────
    // No. 4 — Login Guru Wali (Positif)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_04_guru_wali_dapat_login_dengan_kredensial_valid()
    {
        $this->makeUser('guru_wali', 'guruwali');

        $response = $this->post('/login', [
            'username' => 'guruwali',
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    // ─────────────────────────────────────────────
    // No. 5 — Login (Negatif - username tidak terdaftar)
    // ─────────────────────────────────────────────

    /** @test */
    public function test_05_login_ditolak_dengan_username_tidak_terdaftar()
    {
        $response = $this->post('/login', [
            'username' => 'tidakada',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
