<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class DeleteDataGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_delete_data_guru_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->delete('/data-guru/' . $guru->uuid);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data guru berhasil di hapus!', session('success'));
    }

    public function test_delete_data_guru_failed_because_not_super_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->delete('/data-guru/' . $guru->uuid);
        $response->assertStatus(404);
    }

    public function test_delete_data_guru_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $response = $this->delete('/data-guru/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak valid!', session('error'));
    }

    public function test_delete_data_guru_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->delete('/data-guru/f2a7e279-f7fe-43a2-8a2c-489f3f60574d');
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru gagal di hapus!', session('error'));
    }
}
