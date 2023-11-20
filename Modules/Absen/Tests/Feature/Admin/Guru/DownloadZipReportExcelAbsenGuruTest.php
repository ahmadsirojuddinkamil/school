<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadZipReportExcelAbsenGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_zip_report_absen_excel_guru_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->get('/data-absen/guru/download/excel/zip');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
        unlink('laporan_absen_excel_guru.zip');
    }

    public function test_download_zip_report_absen_excel_guru_failed_because_report_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/download/excel/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen guru tidak ditemukan!', session('error'));
    }

    public function test_download_zip_report_absen_excel_guru_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/download/excel/zip');
        $response->assertStatus(404);
    }
}
