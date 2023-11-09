<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class DownloadZipExcelTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_download_file_zip_excel_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/' . $ppdb->tahun_daftar . '/zip');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
        unlink('laporan_excel_ppdb_tahun_' . $ppdb->tahun_daftar . '.zip');
    }

    public function test_ppdb_download_file_zip_excel_failed_because_not_year(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/tahun/zip');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_download_file_zip_excel_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-ppdb/download/excel/2010/zip');
        $response->assertRedirect('/data-ppdb/tahun-daftar/2010');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data ppdb di tahun ini tidak ada!', session('error'));
    }

    public function test_ppdb_download_file_zip_excel_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/download/excel/' . $ppdb->tahun_daftar . '/zip');
        $response->assertStatus(404);
    }
}
