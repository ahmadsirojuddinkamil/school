<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadPdfAbsenSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_pdf_laporan_absen_siswa_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/laporan-absen/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_download_pdf_laporan_absen_siswa_failed_because_not_siswa(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/laporan-absen/pdf');
        $response->assertStatus(404);
    }

    public function test_download_pdf_laporan_absen_siswa_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/laporan-absen/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen belum ada!', session('error'));
    }
}
