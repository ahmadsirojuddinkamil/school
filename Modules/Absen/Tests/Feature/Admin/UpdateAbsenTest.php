<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Tests\TestCase;

class UpdateAbsenTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_update_date_absen_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->put('/data-absen/date/' . $absen->uuid, [
            'kehadiran' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Berhasil update data absen!', session('success'));
    }

    public function test_update_date_absen_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->put('/data-absen/date/' . $absen->uuid, [
            'kehadiran' => 'hadir',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_date_absen_failed_form_has_not_been(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->put('/data-absen/date/' . $absen->uuid, [
            'kehadiran' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_date_absen_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->put('/data-absen/date/uuid', [
            'kehadiran' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }

    public function test_update_date_absen_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->put('/data-absen/date/c11a8da2-6aa6-47cf-94b6-13a2cbea4993', [
            'kehadiran' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }
}
