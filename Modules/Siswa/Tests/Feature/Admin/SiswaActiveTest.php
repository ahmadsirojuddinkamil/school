<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class SiswaActiveTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_class_siswa_active_page_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);
        Siswa::SiswaActiveFactory()->create();

        $response = $this->get('/data-siswa/status/aktif/kelas');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.list_class');
        $response->assertSeeText('Daftar Kelas');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getListClassSiswa');
        $getListClassSiswa = $response->original->getData()['getListClassSiswa'];
        $this->assertIsArray($getListClassSiswa);
        $this->assertNotEmpty($getListClassSiswa);
    }

    public function test_class_siswa_active_page_failed_displayed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas');
        $response->assertStatus(404);
    }

    public function test_class_siswa_active_page_failed_displayed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/aktif/kelas');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_class_siswa_active_page_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas);
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.show_class');
        $response->assertSeeText('Daftar Siswa Kelas :');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('saveClassFromRoute');
        $saveClassFromRoute = $response->original->getData()['saveClassFromRoute'];
        $this->assertIsString($saveClassFromRoute);
        $this->assertNotEmpty($saveClassFromRoute);

        $response->assertViewHas('getListSiswa');
        $getListSiswa = $response->original->getData()['getListSiswa'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $getListSiswa);
        $this->assertGreaterThan(0, $getListSiswa->count());
    }

    public function test_show_class_siswa_active_page_failed_displayed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas);
        $response->assertStatus(404);
    }

    public function test_show_class_siswa_active_page_failed_displayed_because_not_class(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/target');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak di temukan!', session('error'));
    }

    public function test_show_class_siswa_active_page_failed_displayed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/aktif/kelas/10');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_data_siswa_active_page_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas . '/' . $siswa->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.show_active');
        $response->assertSeeText('Biodata Siswa');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataSiswa', function ($getDataSiswa) {
            return $getDataSiswa !== null && $getDataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas . '/' . $siswa->uuid);
        $response->assertStatus(404);
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_not_class(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/target/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak di temukan!', session('error'));
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/aktif/kelas/10/b615e88d-3568-496e-b5b9-3c1452fff255');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
