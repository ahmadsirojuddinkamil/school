<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadExcelGraduatedTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_graduated_download_file_excel_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/graduated/' . $siswa->uuid . '/download/excel');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_siswa_graduated_download_file_excel_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/graduated/uuid/download/excel');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_siswa_graduated_download_file_excel_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/graduated/99e49795-537b-426d-8425-eb2dc34c22fc/download/excel');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/99e49795-537b-426d-8425-eb2dc34c22fc/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_siswa_graduated_download_file_excel_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/graduated/' . $siswa->uuid . '/download/excel');
        $response->assertStatus(404);
    }
}
