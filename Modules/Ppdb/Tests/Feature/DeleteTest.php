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
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->delete('/ppdb-data/'.$ppdb->uuid);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data ppdb sudah berhasil dihapus!', session('success'));
    }

    public function test_ppdb_delete_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->delete('/ppdb-data/'.$ppdb->uuid);
        $response->assertStatus(404);
    }

    public function test_ppdb_delete_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->delete('/ppdb-data/uuid');
        $response->assertStatus(404);
    }

    public function test_ppdb_delete_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->delete('/ppdb-data/3c07bdfc-c6cd-44a7-85ef-ef4a69724b56');
        $response->assertStatus(404);
    }
}
