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
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/data-ppdb/' . $ppdb->uuid . '/accept');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Peserta ppdb berhasil di terima!', session('success'));
    }

    public function test_ppdb_accept_new_user_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/data-ppdb/' . $ppdb->uuid . '/accept');
        $response->assertStatus(404);
    }

    public function test_ppdb_accept_new_user_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->post('/data-ppdb/uuid/accept');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_accept_new_user_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->post('/data-ppdb/482401ca-cec5-4fb8-8bc6-fc74070073be/accept');
        $response->assertRedirect('/data-ppdb/482401ca-cec5-4fb8-8bc6-fc74070073be');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data peserta ini tidak ada!', session('error'));
    }
}
