<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadZipPdfActiveTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_active_download_file_zip_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/aktif/' . $siswa->kelas . '/download/pdf/zip');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
        unlink('laporan_pdf_siswa_kelas_' . $siswa->kelas . '.zip');
    }

    public function test_siswa_active_download_file_zip_pdf_failed_because_not_class(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/aktif/kelas/download/pdf/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_siswa_active_download_file_zip_pdf_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-siswa/aktif/11/download/pdf/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas/11');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_siswa_active_download_file_zip_pdf_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/aktif/' . $siswa->kelas . '/download/pdf/zip');
        $response->assertStatus(404);
    }
}
