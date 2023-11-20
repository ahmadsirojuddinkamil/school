<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadZipReportExcelAbsenSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_zip_report_absen_excel_siswa_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/data-absen/siswa/' . $siswa->kelas . '/download/excel/zip');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
        unlink('laporan_excel_absen_kelas_' . $siswa->kelas . '.zip');
    }

    public function test_download_zip_report_absen_excel_siswa_failed_because_not_class(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/kelas/download/excel/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak valid!', session('error'));
    }

    public function test_download_zip_report_absen_excel_siswa_failed_because_report_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/11/download/excel/zip');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa/11');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa kelas ini tidak ditemukan!', session('error'));
    }

    public function test_download_zip_report_absen_excel_siswa_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-absen/siswa/' . $siswa->kelas . '/download/excel/zip');
        $response->assertStatus(404);
    }
}
