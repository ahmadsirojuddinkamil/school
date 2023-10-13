<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class AdminShowReportAbsenTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_show_report_absen_page_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        $siswa = Siswa::AdminAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/data-absen/' . $siswa->nisn . '/show');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.siswa.show_report');
        $response->assertSeeText('Laporan Absen');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataAbsen');
        $getDataAbsen = $response->original->getData()['getDataAbsen'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $getDataAbsen);
        $this->assertGreaterThan(0, $getDataAbsen->count());

        $response->assertViewHas('listKehadiran');
        $listKehadiran = $response->original->getData()['listKehadiran'];
        $this->assertIsArray($listKehadiran);
        $this->assertNotEmpty($listKehadiran);

        $response->assertViewHas('listTanggalAbsen');
        $listTanggalAbsen = $response->original->getData()['listTanggalAbsen'];
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $listTanggalAbsen);
        $this->assertFalse($listTanggalAbsen->isEmpty());
    }

    public function test_show_report_absen_page_failed_because_not_nisn(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        Siswa::SiswaGraduatedFactory()->create();
        $response = $this->get('/data-absen/nisn/show');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ditemukan!', session('error'));
    }

    public function test_show_report_absen_page_failed_because_report_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        $siswa = Siswa::AdminAbsenFactory()->create();
        $response = $this->get('/data-absen/' . $siswa->uuid . '/show');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ditemukan!', session('error'));
    }

    public function test_show_report_absen_page_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        $siswa = Siswa::AdminAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/data-absen/' . $siswa->uuid . '/show');
        $response->assertStatus(404);
    }
}
