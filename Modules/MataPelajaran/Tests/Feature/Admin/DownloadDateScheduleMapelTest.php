<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class DownloadDateScheduleMapelTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_pdf_date_schedule_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/pdf/data/materi/' . $mataPelajaran->uuid);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
    }

    public function test_download_pdf_date_schedule_mata_pelajaran_failed_because_name_file_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/file/data/materi/' . $mataPelajaran->uuid);
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_pdf_date_schedule_mata_pelajaran_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/pdf/data/materi/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_pdf_date_schedule_mata_pelajaran_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/pdf/data/materi/0705cf55-395b-46ac-9a15-32e8cfddeedd');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('File materi tidak ditemukan!', session('error'));
    }

    public function test_download_excel_date_schedule_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/excel/data/materi/' . $mataPelajaran->uuid);
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/zip');
    }

    public function test_download_excel_date_schedule_mata_pelajaran_failed_because_name_file_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $response = $this->get('/data-mata-pelajaran/file/data/materi/' . $mataPelajaran->uuid);
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_excel_date_schedule_mata_pelajaran_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/excel/data/materi/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_download_excel_date_schedule_mata_pelajaran_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/excel/data/materi/0705cf55-395b-46ac-9a15-32e8cfddeedd');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('File materi tidak ditemukan!', session('error'));
    }
}
