<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class DownloadExcelTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_download_file_excel_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/' . $ppdb->uuid);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_ppdb_download_file_excel_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/uuid');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_download_file_excel_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/595720eb-765c-4485-926b-5257dbeb0665');
        $response->assertRedirect('/data-ppdb/595720eb-765c-4485-926b-5257dbeb0665');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data peserta ini tidak ada!', session('error'));
    }

    public function test_ppdb_download_file_excel_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/' . $ppdb);
        $response->assertStatus(404);
    }
}
