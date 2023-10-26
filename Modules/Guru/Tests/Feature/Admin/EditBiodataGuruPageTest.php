<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class EditBiodataGuruPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_edit_biodata_guru_page_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-guru/' . $guru->uuid . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('guru::layouts.edit');
        $response->assertSeeText('Biodata Guru');

        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('biodataGuru', function ($biodataGuru) {
            return $biodataGuru !== null && $biodataGuru instanceof \Modules\Guru\Entities\Guru;
        });
    }

    public function test_edit_biodata_guru_page_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-guru/' . $guru->uuid . '/edit');
        $response->assertStatus(404);
    }

    public function test_edit_biodata_guru_page_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-guru/uuid/edit');
        $response->assertStatus(404);
    }

    public function test_edit_biodata_guru_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-guru/f2a7e279-f7fe-43a2-8a2c-489f3f60574d/edit');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Biodata guru tidak ditemukan!', session('error'));
    }
}
