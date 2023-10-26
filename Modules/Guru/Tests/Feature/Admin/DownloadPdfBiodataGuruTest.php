<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class DownloadPdfBiodataGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_pdf_biodata_guru_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->post('/data-guru/' . $guru->uuid . '/download/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_download_pdf_biodata_guru_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->post('/data-guru/' . $guru->uuid . '/download/pdf');
        $response->assertStatus(404);
    }

    public function test_download_pdf_biodata_guru_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->post('/data-guru/uuid/download/pdf');
        $response->assertStatus(404);
    }

    public function test_download_pdf_biodata_guru_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->post('/data-guru/acca9ab0-04dc-40cb-9f36-f6448ab039e1/download/pdf');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Biodata tidak ditemukan!', session('error'));
    }
}
