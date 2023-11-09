<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class CreateGuruPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_create_guru_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
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

    public function test_create_guru_page_failed_because_not_super_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        Guru::factory()->create();
        $response = $this->get('/data-guru/create');
        $response->assertStatus(404);
    }
}
