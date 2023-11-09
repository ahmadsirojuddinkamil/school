<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class UpgradeClassSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_upgrade_class_siswa_active_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $dataSiswa = Siswa::siswaActiveFactory()->count(10)->create();
        foreach ($dataSiswa as $siswa) {
            if ($siswa->kelas == '11') {
                $siswa->delete();
            }
        }

        $response = $this->patch('/data-siswa/status/aktif/kelas', [
            'kelas' => '10'
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Siswa kelas 10 berhasil naik kelas!', session('success'));
    }

    public function test_upgrade_class_siswa_active_failed_because_form_has_not_been(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->patch('/data-siswa/status/aktif/kelas', [
            'kelas' => ''
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_upgrade_class_siswa_active_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $response = $this->patch('/data-siswa/status/aktif/kelas', [
            'kelas' => ''
        ]);
        $response->assertStatus(404);
    }

    public function test_upgrade_class_siswa_active_failed_because_not_class_10_or_11(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->patch('/data-siswa/status/aktif/kelas', [
            'kelas' => '15'
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_upgrade_class_siswa_active_failed_because_class_11_or_12_exists(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Siswa::siswaActiveFactory()->count(10)->create();
        $response = $this->patch('/data-siswa/status/aktif/kelas', [
            'kelas' => '10'
        ]);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Siswa gagal naik kelas, karena masih ada siswa di kelas 11', session('error'));
    }
}
