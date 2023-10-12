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
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->post('/siswa-data/graduated/download/pdf', [
            'tahun_lulus' => $siswa->tahun_lulus,
        ]);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_siswa_graduated_download_file_pdf_failed_because_not_year(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::siswaGraduatedFactory()->create();
        $response = $this->post('/siswa-data/graduated/download/pdf', [
            'tahun_lulus' => 'tahun',
        ]);
        $response->assertStatus(302);
        $errors = session('errors');
        $this->assertTrue($errors->has('tahun_lulus'));
        $this->assertEquals(['The tahun lulus field must be an integer.'], $errors->get('tahun_lulus'));
    }

    public function test_siswa_graduated_download_file_pdf_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::siswaGraduatedFactory()->create();
        $response = $this->post('/siswa-data/download/pdf/', [
            'tahun_lulus' => 5000000,
        ]);
        $response->assertStatus(404);
    }

    public function test_siswa_graduated_download_file_pdf_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->post('/siswa-data/download/pdf/', [
            'tahun_lulus' => $siswa->tahun_lulus,
        ]);
        $response->assertStatus(404);
    }
}
