<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class LaporanAbsenSiswaTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_laporan_absen_siswa_page_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/laporan-absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::pages.absen.laporan');
        $response->assertSeeText('Laporan Absen');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataAbsen');
        $getDataAbsen = $response->original->getData()['getDataAbsen'];
        $this->assertNotNull($getDataAbsen);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $getDataAbsen);
        $this->assertInstanceOf(\Modules\Absen\Entities\Absen::class, $getDataAbsen->first());
    }

    public function test_laporan_absen_siswa_page_failed_because_not_siswa(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/laporan-absen');
        $response->assertStatus(404);
    }

    public function test_laporan_absen_siswa_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $response = $this->get('/laporan-absen');
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen belum ada!', session('error'));
    }
}
