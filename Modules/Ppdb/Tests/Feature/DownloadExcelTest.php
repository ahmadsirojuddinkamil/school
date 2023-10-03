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
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/download/excel/'.$ppdb->tahun_daftar);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_ppdb_download_file_excel_failed_because_not_year(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/download/excel/tahun');
        $response->assertStatus(404);
    }

    public function test_ppdb_download_file_excel_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/download/excel/5000000');
        $response->assertStatus(404);
    }

    public function test_ppdb_download_file_excel_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/download/excel/'.$ppdb);
        $response->assertStatus(404);
    }
}
