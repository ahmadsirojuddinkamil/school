<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class DeleteTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_delete_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->delete('/data-ppdb/' . $ppdb->uuid);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data ppdb sudah berhasil dihapus!', session('success'));
    }

    public function test_ppdb_delete_failed_because_not_role_super_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->delete('/data-ppdb/' . $ppdb->uuid);
        $response->assertStatus(404);
    }

    public function test_ppdb_delete_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->delete('/data-ppdb/uuid');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_delete_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->delete('/data-ppdb/3c07bdfc-c6cd-44a7-85ef-ef4a69724b56');
        $response->assertRedirect('/data-ppdb/3c07bdfc-c6cd-44a7-85ef-ef4a69724b56');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data peserta ini tidak ada!', session('error'));
    }
}
