<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadPdfActiveTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_admin_siswa_active_download_file_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/aktif/' . $siswa->uuid . '/download/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_user_siswa_active_download_file_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/data-siswa/aktif/' . $siswa->uuid . '/download/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_siswa_active_download_file_pdf_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/aktif/uuid/download/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_siswa_active_download_file_pdf_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-siswa/aktif/99e49795-537b-426d-8425-eb2dc34c22fc/download/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_siswa_active_download_file_pdf_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/aktif/' . $siswa->uuid . '/download/pdf');
        $response->assertStatus(404);
    }
}
