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
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);
        Siswa::SiswaActiveFactory()->count(5)->create();

        $response = $this->get('/data-siswa/status/aktif/kelas');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.list_class');
        $response->assertSeeText('Daftar Kelas');

        $response->assertViewHas('listSiswaInClass');
        $listSiswaInClass = $response->original->getData()['listSiswaInClass'];
        $this->assertIsArray($listSiswaInClass);
        $this->assertNotEmpty($listSiswaInClass);
    }

    public function test_class_siswa_active_page_failed_displayed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas');
        $response->assertStatus(404);
    }

    public function test_class_siswa_active_page_failed_displayed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/aktif/kelas');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_class_siswa_active_page_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas);
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.show_class');
        $response->assertSeeText('Daftar Siswa Kelas :');

        $response->assertViewHas('saveClassFromCall');
        $saveClassFromCall = $response->original->getData()['saveClassFromCall'];
        $this->assertIsString($saveClassFromCall);
        $this->assertNotEmpty($saveClassFromCall);

        $response->assertViewHas('listSiswa');
        $listSiswa = $response->original->getData()['listSiswa'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $listSiswa);
        $this->assertGreaterThan(0, $listSiswa->count());
    }

    public function test_show_class_siswa_active_page_failed_displayed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas);
        $response->assertStatus(404);
    }

    public function test_show_class_siswa_active_page_failed_displayed_because_not_class(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/50');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_show_class_siswa_active_page_failed_displayed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/aktif/kelas/10');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_data_siswa_active_page_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas . '/' . $siswa->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.show_active');
        $response->assertSeeText('Biodata Siswa');

        $response->assertViewHas('dataSiswa', function ($dataSiswa) {
            return $dataSiswa !== null && $dataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/' . $siswa->kelas . '/' . $siswa->uuid);
        $response->assertStatus(404);
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_not_class(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/20/b9675844-a4ca-46f9-a9c0-b9fa2296ba29');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/status/aktif/kelas/10/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas/10');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_show_data_siswa_active_page_failed_displayed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-siswa/status/aktif/kelas/10/b615e88d-3568-496e-b5b9-3c1452fff255');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas/10');
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
