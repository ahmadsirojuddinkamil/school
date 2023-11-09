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
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);
        Siswa::siswaGraduatedFactory()->count(10)->create();

        $response = $this->get('/data-siswa/status/sudah-lulus');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.graduated');
        $response->assertSeeText('Daftar Siswa Lulus');

        $response->assertViewHas('dataSiswa');
        $dataSiswa = $response->original->getData()['dataSiswa'];
        $this->assertNotEmpty($dataSiswa);

        $response->assertViewHas('listYearGraduated');
        $listYearGraduated = $response->original->getData()['listYearGraduated'];
        $this->assertIsArray($listYearGraduated);
        $this->assertNotEmpty($listYearGraduated);
    }

    public function test_status_siswa_graduated_page_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/sudah-lulus');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa lulus tidak ditemukan!', session('error'));
    }

    public function test_show_siswa_graduated_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);
        $siswa = Siswa::siswaGraduatedFactory()->create();

        $response = $this->get('/data-siswa/' . $siswa->uuid . '/status/sudah-lulus');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.show_graduated');
        $response->assertSeeText('Biodata Siswa');

        $response->assertViewHas('dataSiswa', function ($dataSiswa) {
            return $dataSiswa !== null && $dataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });
    }

    public function test_show_siswa_graduated_page_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->get('/data-siswa/' . $siswa->uuid . '/status/sudah-lulus');
        $response->assertStatus(404);
    }

    public function test_show_siswa_graduated_page_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/uuid/status/sudah-lulus');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_show_siswa_graduated_page_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $responseNotFoundUuid = $this->get('/data-siswa/3fee2c98-ee76-4972-96ad-53bdad460df8/status/sudah-lulus');
        $responseNotFoundUuid->assertStatus(302);
        $responseNotFoundUuid->assertRedirect('/data-siswa/status/sudah-lulus');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
