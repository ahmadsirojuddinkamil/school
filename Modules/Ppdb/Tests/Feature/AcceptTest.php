<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class AcceptTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_accept_new_user_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/' . $ppdb->uuid . '/accept');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Peserta ppdb berhasil menjadi siswa!', session('success'));
    }

    public function test_ppdb_accept_new_user_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/' . $ppdb->uuid . '/accept');
        $response->assertStatus(404);
    }

    public function test_ppdb_accept_new_user_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/uuid/accept');
        $response->assertStatus(404);
    }

    public function test_ppdb_accept_new_user_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/482401ca-cec5-4fb8-8bc6-fc74070073be/accept');
        $response->assertStatus(404);
    }
}
