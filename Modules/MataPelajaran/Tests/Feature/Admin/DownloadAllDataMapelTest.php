<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class DownloadAllDataMapelTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_pdf_all_data_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/download-all-data/pdf');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
    }

    public function test_download_pdf_all_data_mata_pelajaran_failed_because_name_file_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/download-all-data/extensi');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_pdf_all_data_mata_pelajaran_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/download-all-data/pdf');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak ditemukan!', session('error'));
    }

    public function test_download_excel_all_data_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/download-all-data/excel');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
    }

    public function test_download_excel_all_data_mata_pelajaran_failed_because_name_file_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/download-all-data/extensi');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_excel_all_data_mata_pelajaran_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/download-all-data/excel');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak ditemukan!', session('error'));
    }
}
