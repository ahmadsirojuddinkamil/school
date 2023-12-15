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
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Guru::factory(10)->create();
        $response = $this->get('/data-guru');
        $response->assertStatus(200);
        $response->assertViewIs('guru::layouts.Admin.list_guru');
        $response->assertSeeText('Data Guru');

        $response->assertViewHas('dataGuru');
        $dataGuru = $response->original->getData()['dataGuru'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $dataGuru);
        $this->assertGreaterThan(0, $dataGuru->count());
    }

    public function test_list_guru_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        Guru::factory(10)->create();
        $response = $this->get('/data-guru');
        $response->assertStatus(404);
    }
}
