<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class DeleteBiodataGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_delete_biodata_guru_success(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->delete('/data-guru/' . $guru->uuid);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Biodata guru berhasil di hapus!', session('success'));
    }

    public function test_delete_biodata_guru_failed_because_not_super_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->delete('/data-guru/' . $guru->uuid);
        $response->assertStatus(404);
    }

    public function test_delete_biodata_guru_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $response = $this->delete('/data-guru/uuid');
        $response->assertStatus(404);
    }

    public function test_delete_biodata_guru_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->delete('/data-guru/f2a7e279-f7fe-43a2-8a2c-489f3f60574d');
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Biodata guru gagal di hapus!', session('error'));
    }
}
