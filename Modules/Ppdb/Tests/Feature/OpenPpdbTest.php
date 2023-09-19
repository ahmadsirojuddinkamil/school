<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\OpenPpdb;
use Tests\TestCase;

class OpenPpdbTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_open_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->post('/ppdb-data/open', [
            'uuid' => '95d4c1b1-b856-41b5-90f7-62aa38673bd1',
            'tanggal_mulai' => '2023-09-18',
            'tanggal_akhir' => '2023-09-19',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Berhasil membuka pendaftaran ppdb!', session('success'));
    }

    public function test_ppdb_close_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);
        OpenPpdb::factory()->create();

        $response = $this->delete('/ppdb-data/status');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Berhasil menutup pendaftaran ppdb!', session('success'));
    }
}
