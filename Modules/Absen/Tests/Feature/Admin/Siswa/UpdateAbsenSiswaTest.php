<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class UpdateAbsenSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_update_date_absen_siswa_success(): void
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

        $response = $this->put('/data-absen/siswa/date/' . $absen->uuid, [
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa/' . $absen->status);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Berhasil update data absen!', session('success'));
    }

    public function test_update_date_absen_siswa_failed_because_not_admin(): void
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

        $response = $this->put('/data-absen/siswa/date/' . $absen->uuid, [
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_date_absen_siswa_failed_form_is_empty(): void
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

        $response = $this->put('/data-absen/siswa/date/' . $absen->uuid, [
            'keterangan' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_date_absen_siswa_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->put('/data-absen/siswa/date/uuid', [
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak valid!', session('error'));
    }

    public function test_update_date_absen_siswa_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->put('/data-absen/siswa/date/c11a8da2-6aa6-47cf-94b6-13a2cbea4993', [
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
