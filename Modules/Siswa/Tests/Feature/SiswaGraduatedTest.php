<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class SiswaGraduatedTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_status_siswa_graduated_page_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);
        Siswa::siswaGraduatedFactory()->create();

        $response = $this->get('/siswa-data/status/sudah-lulus');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::pages.siswa.graduated');
        $response->assertSeeText('Daftar Siswa Lulus');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getSiswaGraduated');
        $getSiswaGraduated = $response->original->getData()['getSiswaGraduated'];
        $this->assertNotEmpty($getSiswaGraduated);
    }

    public function test_status_siswa_graduated_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/siswa-data/status/sudah-lulus');
        $response->assertStatus(302);
        $response->assertRedirect('/siswa-data/status');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa lulus tidak ditemukan!', session('error'));
    }

    public function test_show_siswa_graduated_page_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);
        $siswa = Siswa::siswaGraduatedFactory()->create();

        $response = $this->get('/siswa-data/' . $siswa->uuid . '/status/sudah-lulus');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::pages.siswa.show_graduated');
        $response->assertSeeText('Biodata Siswa');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataSiswa', function ($getDataSiswa) {
            return $getDataSiswa !== null && $getDataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });
    }

    public function test_show_siswa_graduated_page_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->get('/siswa-data/' . $siswa->uuid . '/status/sudah-lulus');
        $response->assertStatus(404);
    }

    public function test_show_siswa_graduated_page_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotUuid = $this->get('/siswa-data/uuid/status/sudah-lulus');
        $responseParameterNotUuid->assertStatus(302);
        $responseParameterNotUuid->assertRedirect('/siswa-data/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_siswa_graduated_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseNotFoundUuid = $this->get('/siswa-data/3fee2c98-ee76-4972-96ad-53bdad460df8/status/sudah-lulus');
        $responseNotFoundUuid->assertStatus(302);
        $responseNotFoundUuid->assertRedirect('/siswa-data/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
