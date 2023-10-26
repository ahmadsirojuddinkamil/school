<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class AdminAbsenTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_list_class_absen_page_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        Siswa::AdminAbsenFactory()->create();
        $response = $this->get('/data-absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.siswa.list_class');
        $response->assertSeeText('Daftar Absen Siswa');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);
    }

    public function test_list_class_absen_page_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        Siswa::AdminAbsenFactory()->create();
        $response = $this->get('/data-absen');
        $response->assertStatus(404);
    }
}
