<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class DeleteMataPelajaranTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_delete_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->delete('/data-mata-pelajaran/' . $mataPelajaran->uuid . '/delete');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Mata pelajaran ' . $mataPelajaran->name . ' berhasil di hapus!', session('success'));
    }

    public function test_delete_mata_pelajaran_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->delete('/data-mata-pelajaran/' . $mataPelajaran->uuid . '/delete');
        $response->assertStatus(404);
    }

    public function test_delete_mata_pelajaran_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
        $this->actingAs($user);

        $response = $this->delete('/data-mata-pelajaran/uuid/delete');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_delete_mata_pelajaran_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
        $this->actingAs($user);

        $response = $this->delete('/data-mata-pelajaran/b86b2a0f-095c-4e8c-a876-90677e9b50e2/delete');
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak ditemukan!', session('error'));
    }
}
