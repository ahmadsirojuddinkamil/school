<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class SiswaActiveToGraduateTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_active_to_graduate_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Siswa::siswaActiveFactory()->count(10)->create();
        $response = $this->patch('/data-siswa/status/aktif/lulus', [
            'kelas' => '12'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Siswa kelas 12 berhasil lulus!', session('success'));
    }

    public function test_siswa_active_to_graduate_failed_because_form_has_not_been(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->patch('/data-siswa/status/aktif/lulus', [
            'kelas' => ''
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_siswa_active_to_graduate_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $response = $this->patch('/data-siswa/status/aktif/lulus', [
            'kelas' => '12'
        ]);
        $response->assertStatus(404);
    }

    public function test_siswa_active_to_graduate_failed_because_not_class_12(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->patch('/data-siswa/status/aktif/lulus', [
            'kelas' => '15'
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_siswa_active_to_graduate_failed_because_class_12_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $dataSiswa = Siswa::siswaActiveFactory()->count(10)->create();

        foreach ($dataSiswa as $siswa) {
            if ($siswa->kelas == '12') {
                $siswa->delete();
            }
        }

        $response = $this->patch('/data-siswa/status/aktif/lulus', [
            'kelas' => '12'
        ]);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
