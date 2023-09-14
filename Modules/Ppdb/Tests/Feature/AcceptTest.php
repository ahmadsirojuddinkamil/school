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

    public function test_ppdb_success_accept_new_user(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->post('/ppdb-data/'.$ppdb->uuid);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Peserta ppdb berhasil menjadi siswa!', session('success'));
    }

    public function test_ppdb_failed_accept_new_user(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotUuid = $this->post('/ppdb-data/coding');
        $responseParameterNotUuid->assertStatus(404);

        $responseNotFoundUuid = $this->post('/ppdb-data/3000');
        $responseNotFoundUuid->assertStatus(404);
    }
}
