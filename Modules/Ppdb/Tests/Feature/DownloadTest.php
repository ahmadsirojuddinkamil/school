<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_success_download_file_pdf(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/download/pdf/'.$ppdb->tahun_daftar);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_ppdb_failed_download_file_pdf(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotYear = $this->post('/ppdb-data/download/pdf/coding');
        $responseParameterNotYear->assertStatus(404);

        $responseNotFoundYear = $this->post('/ppdb-data/download/pdf/3000');
        $responseNotFoundYear->assertStatus(404);
    }

    public function test_ppdb_success_download_file_excel(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/download/excel/'.$ppdb->tahun_daftar);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_ppdb_failed_download_file_excel(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotYear = $this->post('/ppdb-data/download/excel/coding');
        $responseParameterNotYear->assertStatus(404);

        $responseNotFoundYear = $this->post('/ppdb-data/download/excel/3000');
        $responseNotFoundYear->assertStatus(404);
    }
}
