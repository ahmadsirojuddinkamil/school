<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DeleteReportAbsenGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_delete_report_absen_guru_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->delete('/data-absen/guru/report', [
            'uuid' => $guru->uuid->toString(),
        ]);

        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data laporan absen berhasil dihapus!', session('success'));
    }

    public function test_delete_report_absen_guru_failed_because_not_super_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $response = $this->delete('/data-absen/guru/report', [
            'uuid' => '2736a69e-7a56-4d30-b5ab-7d3b4ffbaf0d',
        ]);
        $response->assertStatus(404);
    }

    public function test_delete_report_absen_guru_failed_because_form_is_empty(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->delete('/data-absen/guru/report', [
            'uuid' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_delete_report_absen_guru_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        session(['userData' => [$user, 'super_admin']]);
        $this->actingAs($user);

        $siswa = Guru::factory()->create();
        $response = $this->delete('/data-absen/guru/report', [
            'uuid' => $siswa->uuid->toString(),
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data laporan absen tidak ditemukan!', session('error'));
    }
}
