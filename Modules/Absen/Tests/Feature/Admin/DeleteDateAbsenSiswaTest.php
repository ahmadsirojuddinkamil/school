<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DeleteDateAbsenSiswaTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_delete_tanggal_absen_siswa_success(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->delete('/data-absen/tanggal', [
            'nisn' => $user->siswa->nisn,
            'tanggal' => $absen->created_at->format('Y-m-d H:i:s'),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data tanggal absen berhasil dihapus!', session('success'));
    }

    public function test_delete_tanggal_absen_siswa_failed_because_not_super_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->delete('/data-absen/tanggal', [
            'nisn' => $user->siswa->nisn,
            'tanggal' => $absen->created_at->format('Y-m-d H:i:s'),
        ]);
        $response->assertStatus(404);
    }

    public function test_delete_tanggal_absen_siswa_failed_because_form_has_not_been(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->delete('/data-absen/tanggal', [
            'nisn' => '',
            'tanggal' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_delete_tanggal_absen_siswa_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->delete('/data-absen/tanggal', [
            'nisn' => '0382304230',
            'tanggal' => $absen->created_at->format('Y-m-d H:i:s'),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }
}
