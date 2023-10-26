<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class ListGuruPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_list_guru_page_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Guru::factory(10)->create();
        $response = $this->get('/data-guru');
        $response->assertStatus(200);
        $response->assertViewIs('guru::layouts.Admin.list_guru');
        $response->assertSeeText('Data Guru');

        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataGuru');
        $getDataGuru = $response->original->getData()['getDataGuru'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $getDataGuru);
        $this->assertGreaterThan(0, $getDataGuru->count());
    }

    public function test_list_guru_page_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Guru::factory(10)->create();
        $response = $this->get('/data-guru');
        $response->assertStatus(404);
    }

    public function test_list_guru_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-guru');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak ditemukan!', session('error'));
    }
}
