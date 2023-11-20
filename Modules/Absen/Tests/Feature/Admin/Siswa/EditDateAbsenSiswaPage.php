<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class EditDateAbsenSiswaPage extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_edit_date_absen_siswa_page_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/data-absen/siswa/' . $absen->uuid . '/' . $absen->created_at . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.siswa.edit_date');
        $response->assertSeeText('Edit data absen');

        $response->assertViewHas('dataAbsen', function ($dataAbsen) {
            return $dataAbsen !== null && $dataAbsen instanceof \Modules\Absen\Entities\Absen;
        });
    }

    public function test_edit_date_absen_siswa_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/data-absen/siswa/' . $absen->uuid . '/' . $absen->created_at . '/edit');
        $response->assertStatus(404);
    }

    public function test_edit_date_absen_siswa_page_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/data-absen/siswa/uuid/' . $absen->created_at . '/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak valid!', session('error'));
    }

    public function test_edit_date_absen_siswa_page_failed_because_not_date(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/data-absen/siswa/' . $absen->uuid . '/date/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }

    public function test_edit_date_absen_siswa_page_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/2736a69e-7a56-4d30-b5ab-7d3b4ffbaf0d/2023-11-20 09:16:59/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ditemukan!', session('error'));
    }
}
