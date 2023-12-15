<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class DownloadPdfDataGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_admin_download_pdf_biodata_guru_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-guru/' . $guru->uuid . '/download/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_user_download_pdf_biodata_guru_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $guru->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/data-guru/' . $guru->uuid . '/download/pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_download_pdf_biodata_guru_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-guru/' . $guru->uuid . '/download/pdf');
        $response->assertStatus(404);
    }

    public function test_download_pdf_biodata_guru_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->get('/data-guru/uuid/download/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak valid!', session('error'));
    }

    public function test_download_pdf_biodata_guru_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->get('/data-guru/acca9ab0-04dc-40cb-9f36-f6448ab039e1/download/pdf');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Biodata tidak ditemukan!', session('error'));
    }
}
