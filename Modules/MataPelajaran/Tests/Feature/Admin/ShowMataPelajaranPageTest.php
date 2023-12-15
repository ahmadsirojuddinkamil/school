<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class ShowMataPelajaranPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_show_mata_pelajaran_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->get('/data-mata-pelajaran/' . $mataPelajaran->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('matapelajaran::layouts.Admin.show');
        $response->assertSeeText('Mata Pelajaran');

        $response->assertViewHas('dataMataPelajaran', function ($dataMataPelajaran) {
            return $dataMataPelajaran !== null && $dataMataPelajaran instanceof \Modules\MataPelajaran\Entities\MataPelajaran;
        });
    }

    public function test_show_mata_pelajaran_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->get('/data-mata-pelajaran/' . $mataPelajaran->uuid);
        $response->assertStatus(404);
    }

    public function test_show_mata_pelajaran_page_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_show_mata_pelajaran_page_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran/b86b2a0f-095c-4e8c-a876-90677e9b50e2');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak di temukan!', session('error'));
    }
}
