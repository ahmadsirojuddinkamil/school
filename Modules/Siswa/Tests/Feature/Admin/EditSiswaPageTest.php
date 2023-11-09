<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class EditSiswaPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_admin_edit_siswa_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $userSiswa = $this->roleService->createUserSiswa();
        $siswa->update([
            'user_uuid' => $userSiswa->uuid,
        ]);

        $response = $this->get('/data-siswa/' . $siswa->uuid . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.edit');
        $response->assertSeeText('Edit data siswa');

        $response->assertViewHas('dataSiswa', function ($dataSiswa) {
            return $dataSiswa !== null && $dataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });

        $response->assertViewHas('timeBox', function ($timeBox) {
            return is_array($timeBox);
        });
    }

    public function test_user_edit_siswa_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/data-siswa/' . $siswa->uuid . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::layouts.admin.edit');
        $response->assertSeeText('Edit data siswa');

        $response->assertViewHas('dataSiswa', function ($dataSiswa) {
            return $dataSiswa !== null && $dataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });

        $response->assertViewHas('timeBox', function ($timeBox) {
            return is_array($timeBox);
        });
    }

    public function test_edit_siswa_page_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/' . $siswa->uuid . '/edit');
        $response->assertStatus(404);
    }

    public function test_edit_siswa_page_failed_displayed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-siswa/uuid/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_edit_siswa_page_failed_displayed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaActiveFactory()->create();
        $response = $this->get('/data-siswa/538e652b-81bf-4d2c-94ac-194ffdf515c1/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
