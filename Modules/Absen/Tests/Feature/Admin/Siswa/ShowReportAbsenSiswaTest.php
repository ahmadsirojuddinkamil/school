<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class ShowReportAbsenSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_show_report_absen_siswa_page_success_is_displayed(): void
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

        $response = $this->get('/data-absen/siswa/' . $siswa->uuid . '/show');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.siswa.show_report');
        $response->assertSeeText('Laporan Absen');

        $response->assertViewHas('dataSiswa', function ($dataSiswa) {
            return $dataSiswa !== null && $dataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });

        $response->assertViewHas('listKehadiran');
        $listKehadiran = $response->original->getData()['listKehadiran'];
        $this->assertIsArray($listKehadiran);
        $this->assertNotEmpty($listKehadiran);

        $response->assertViewHas('listAbsen');
        $listAbsen = $response->original->getData()['listAbsen'];
        $this->assertNotNull($listAbsen);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $listAbsen);
        $this->assertInstanceOf(\Modules\Absen\Entities\Absen::class, $listAbsen->first());
    }

    public function test_show_report_absen_siswa_page_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/uuid/show');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak valid!', session('error'));
    }

    public function test_show_report_absen_siswa_page_failed_because_report_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/27dceb25-ff7f-41bf-aebf-9e10f3956350/show');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_report_absen_siswa_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/27dceb25-ff7f-41bf-aebf-9e10f3956350/show');
        $response->assertStatus(404);
    }
}
