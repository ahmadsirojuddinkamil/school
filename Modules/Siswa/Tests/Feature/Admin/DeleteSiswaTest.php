<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DeleteSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_delete_siswa_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->delete('/data-siswa/' . $siswa->uuid);
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data siswa berhasil di hapus!', session('success'));
    }

    public function test_delete_siswa_failed_because_not_role_super_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->delete('/data-siswa/' . $siswa->uuid);
        $response->assertStatus(404);
    }

    public function test_delete_siswa_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $response = $this->delete('/data-siswa/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_delete_siswa_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $response = $this->delete('/data-siswa/4824e120-a611-4273-b0d4-7a0f7dbaa3f8');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
