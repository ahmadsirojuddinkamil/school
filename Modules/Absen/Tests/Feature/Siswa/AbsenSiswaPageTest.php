<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class AbsenSiswaPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_absen_siswa_page_before_absen_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.create_absen');

        // false
        $response->assertViewHas('checkAbsenOrNot');
        $checkAbsenOrNot = $response->original->getData()['checkAbsenOrNot'];
        $this->assertFalse($checkAbsenOrNot);
    }

    public function test_absen_siswa_page_after_absen_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $absen = Absen::AfterAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.create_absen');
        $response->assertSeeText('Absen');

        // true
        $response->assertViewHas('checkAbsenOrNot');
        $checkAbsenOrNot = $response->original->getData()['checkAbsenOrNot'];
        $this->assertTrue($checkAbsenOrNot);
    }

    public function test_absen_siswa_page_failed_because_not_siswa(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/absen');
        $response->assertStatus(404);
    }
}
