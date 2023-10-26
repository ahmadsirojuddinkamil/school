<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Tests\TestCase;

class DownloadZipAbsenSiswaTest extends TestCase
{
    // use RefreshDatabase, DatabaseMigrations;
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_zip_laporan_absen_class_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Absen::LaporanZipAbsenSiswaFactory()->count(50)->create();
        $response = $this->post('/data-absen/download/zip/class', [
            'kelas' => '10',
        ]);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');

        unlink('laporan_absen_kelas_10.zip');
    }

    public function test_download_zip_laporan_absen_class_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Absen::LaporanZipAbsenSiswaFactory()->count(50)->create();
        $response = $this->post('/data-absen/download/zip/class', [
            'kelas' => '10',
        ]);
        $response->assertStatus(404);
    }

    public function test_download_zip_laporan_absen_class_failed_because_form_has_not_been(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Absen::LaporanZipAbsenSiswaFactory()->count(50)->create();
        $response = $this->post('/data-absen/download/zip/class', [
            'kelas' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_download_zip_laporan_absen_class_failed_because_class_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Absen::LaporanZipAbsenSiswaFactory()->count(50)->create();
        $response = $this->post('/data-absen/download/zip/class', [
            'kelas' => '20',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak di temukan!', session('error'));
    }

    public function test_download_zip_laporan_absen_class_failed_because_absen_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->post('/data-absen/download/zip/class', [
            'kelas' => '11',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ada!', session('error'));
    }
}
