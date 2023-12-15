<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadZipPdfGraduatedTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_graduated_download_file_zip_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/graduated/' . $siswa->tahun_keluar . '/download/pdf/zip');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
        unlink('laporan_pdf_siswa_lulus_tahun_' . $siswa->tahun_keluar . '.zip');
    }

    public function test_siswa_graduated_download_file_zip_pdf_failed_because_not_year(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/graduated/tahun/download/pdf/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_siswa_graduated_download_file_zip_pdf_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-siswa/graduated/2023/download/pdf/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa lulus tidak ditemukan!', session('error'));
    }

    public function test_siswa_graduated_download_file_zip_pdf_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::SiswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/graduated/' . $siswa->tahun_keluar . '/download/pdf/zip');
        $response->assertStatus(404);
    }
}
