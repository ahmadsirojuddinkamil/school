<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class SiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_status_siswa_page_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.status');
        $response->assertSeeText('Status Siswa');

        $response->assertViewHas('statusSiswa');
        $statusSiswa = $response->original->getData()['statusSiswa'];
        $this->assertIsArray($statusSiswa);
        $this->assertNotEmpty($statusSiswa);
    }

    public function test_status_siswa_page_failed_displayed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status');
        $response->assertStatus(404);
    }
}
