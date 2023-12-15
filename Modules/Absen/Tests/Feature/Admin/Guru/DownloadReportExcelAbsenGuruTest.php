<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadReportExcelAbsenGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_report_absen_excel_guru_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->get('/data-absen/guru/' . $guru->uuid . '/download/excel');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_download_report_absen_excel_guru_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/uuid/download/excel');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak valid!', session('error'));
    }

    public function test_download_report_absen_excel_guru_failed_because_report_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->get('/data-absen/guru/' . $guru->uuid . '/download/excel');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru/' . $guru->uuid);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ditemukan!', session('error'));
    }

    public function test_download_report_absen_excel_guru_failed_because_guru_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/2736a69e-7a56-4d30-b5ab-7d3b4ffbaf0d/download/excel');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru/2736a69e-7a56-4d30-b5ab-7d3b4ffbaf0d');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak ditemukan!', session('error'));
    }

    public function test_download_report_absen_excel_guru_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->get('/data-absen/guru/' . $guru->uuid . '/download/excel');
        $response->assertStatus(404);
    }
}
