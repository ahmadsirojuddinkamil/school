<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class StoreAbsenSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_store_absen_siswa_success(): void
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

        $response = $this->post('/absen', [
            'status' => $siswa->kelas,
            'persetujuan' => 'setuju',
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/absen');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Anda berhasil melakukan absen!', session('success'));
    }

    public function test_store_absen_siswa_failed_because_not_siswa(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->post('/absen', [
            'status' => '10',
            'persetujuan' => 'setuju',
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(404);
    }

    public function test_store_absen_siswa_failed_because_form_is_empty(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $response = $this->post('/absen', [
            'status' => '',
            'persetujuan' => '',
            'keterangan' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_store_absen_siswa_failed_because_absen_today_exists(): void
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

        $response = $this->post('/absen', [
            'status' => $siswa->kelas,
            'persetujuan' => 'setuju',
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Anda sudah melakukan absen!', session('error'));
    }
}
