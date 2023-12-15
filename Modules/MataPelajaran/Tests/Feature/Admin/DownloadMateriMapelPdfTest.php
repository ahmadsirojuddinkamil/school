<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class DownloadMateriMapelPdfTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_materi_mata_pelajaran_pdf_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/' . $mataPelajaran->uuid . '/materi/pdf');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
    }

    public function test_download_materi_mata_pelajaran_pdf_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/uuid/materi/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_materi_mata_pelajaran_pdf_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/b86b2a0f-095c-4e8c-a876-90677e9b50e2/materi/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('File materi PDF tidak ditemukan!', session('error'));
    }
}
