<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class CreateNewGuruPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_create_new_guru_page_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->get('/data-guru/create');
        $response->assertStatus(200);
        $response->assertViewIs('guru::layouts.Admin.create');
        $response->assertSeeText('Form Guru Baru');

        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);
    }

    public function test_create_new_guru_page_failed_because_not_super_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->get('/data-guru/create');
        $response->assertStatus(404);
    }
}
