<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class CreateSiswaPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_create_siswa_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-siswa/create');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.create');
        $response->assertSeeText('Tambah Siswa Baru');
    }

    public function test_create_siswa_page_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/create');
        $response->assertStatus(404);
    }
}
