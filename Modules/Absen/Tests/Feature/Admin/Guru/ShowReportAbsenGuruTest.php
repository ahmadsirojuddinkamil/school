<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class ShowReportAbsenGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_show_report_absen_guru_page_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->get('/data-absen/guru/' . $guru->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.guru.report');
        $response->assertSeeText('Laporan Absen');

        $response->assertViewHas('dataGuru', function ($dataGuru) {
            return $dataGuru !== null && $dataGuru instanceof \Modules\Guru\Entities\Guru;
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

    public function test_show_report_absen_guru_page_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/uuid');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak valid!', session('error'));
    }

    public function test_show_report_absen_guru_page_failed_because_guru_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/27dceb25-ff7f-41bf-aebf-9e10f3956350');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak ditemukan!', session('error'));
    }

    public function test_show_report_absen_guru_page_failed_because_report_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-absen/guru/' . $guru->uuid);
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ditemukan!', session('error'));
    }

    public function test_show_report_absen_guru_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru/27dceb25-ff7f-41bf-aebf-9e10f3956350/show');
        $response->assertStatus(404);
    }
}
