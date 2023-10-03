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
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/siswa-data/status');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::pages.siswa.status');
        $response->assertSeeText('Status Siswa');
        $response->assertViewHas('dataUserAuth');

        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getStatusSiswa');
        $getStatusSiswa = $response->original->getData()['getStatusSiswa'];
        $this->assertIsArray($getStatusSiswa);
        $this->assertNotEmpty($getStatusSiswa);
    }

    public function test_status_siswa_page_failed_displayed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::factory()->create();
        $response = $this->get('/siswa-data/status');
        $response->assertStatus(404);
    }
}
