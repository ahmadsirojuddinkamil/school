<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadPdfActiveTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_active_download_file_pdf_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::factory()->create();
        $response = $this->post('/siswa-data/aktif/download/pdf', [
            'kelas' => $siswa->kelas,
        ]);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_siswa_active_download_file_pdf_failed_because_not_class(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::factory()->create();
        $response = $this->post('/siswa-data/aktif/download/pdf', [
            'kelas' => 'kelas',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak di temukan!', session('error'));
    }

    public function test_siswa_active_download_file_pdf_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->post('/siswa-data/aktif/download/pdf', [
            'kelas' => '10',
        ]);
        $response->assertStatus(404);
    }

    public function test_siswa_active_download_file_pdf_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::factory()->create();
        $response = $this->post('/siswa-data/aktif/download/pdf', [
            'kelas' => $siswa->kelas,
        ]);
        $response->assertStatus(404);
    }
}
