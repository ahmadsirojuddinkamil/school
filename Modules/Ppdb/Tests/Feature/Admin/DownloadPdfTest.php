<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class DownloadPdfTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_download_file_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/pdf/' . $ppdb->uuid);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_ppdb_download_file_pdf_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/pdf/uuid');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_download_file_pdf_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/pdf/79d3b294-6b10-4dc1-aa79-b093df066457');
        $response->assertRedirect('/data-ppdb/79d3b294-6b10-4dc1-aa79-b093df066457');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data peserta ini tidak ada!', session('error'));
    }

    public function test_ppdb_download_file_pdf_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/pdf/' . $ppdb->uuid);
        $response->assertStatus(404);
    }
}
