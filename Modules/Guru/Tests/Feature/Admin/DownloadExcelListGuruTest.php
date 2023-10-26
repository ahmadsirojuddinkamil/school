<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class DownloadExcelListGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_excel_list_guru_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Guru::factory(10)->create();
        $response = $this->post('/data-guru/download/zip/excel');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');

        unlink('excel_daftar_biodata_guru.zip');
    }

    public function test_download_excel_list_guru_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->post('/data-guru/download/zip/excel');
        $response->assertStatus(404);
    }

    public function test_download_excel_list_guru_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->post('/data-guru/download/zip/excel');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Biodata guru tidak ditemukan!', session('error'));
    }
}
