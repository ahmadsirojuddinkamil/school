<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadPdfGraduatedTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_graduated_download_file_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/graduated/' . $siswa->uuid . '/download/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_siswa_graduated_download_file_pdf_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/graduated/uuid/download/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_siswa_graduated_download_file_pdf_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/graduated/250400a8-a8e7-4247-91ee-9ad8fa1f5cb4/download/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/250400a8-a8e7-4247-91ee-9ad8fa1f5cb4/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_siswa_graduated_download_file_pdf_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/graduated/' . $siswa->uuid . '/download/pdf');
        $response->assertStatus(404);
    }
}
