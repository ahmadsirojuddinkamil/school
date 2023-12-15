<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class LaporanAbsenGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_laporan_absen_guru_page_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $guru->update([
            'user_uuid' => $user->uuid,
        ]);

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->get('/laporan-absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.laporan');
        $response->assertSeeText('Laporan Absen');

        $response->assertViewHas('listAbsen');
        $listAbsen = $response->original->getData()['listAbsen'];
        $this->assertNotNull($listAbsen);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $listAbsen);
        $this->assertInstanceOf(\Modules\Absen\Entities\Absen::class, $listAbsen->first());

        $response->assertViewHas('uuidRole');
        $uuidRole = $response->original->getData()['uuidRole'];
        $this->assertNotNull($uuidRole);
        $pattern = '/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i';
        $this->assertMatchesRegularExpression($pattern, $uuidRole);

        $response->assertViewHas('listKehadiran');
        $listKehadiran = $response->original->getData()['listKehadiran'];
        $this->assertNotNull($listKehadiran);
        $this->assertIsArray($listKehadiran);
    }

    public function test_laporan_absen_guru_page_failed_because_not_guru(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/laporan-absen');
        $response->assertStatus(404);
    }

    public function test_laporan_absen_guru_page_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $guru->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/laporan-absen');
        $response->assertStatus(302);
        $response->assertRedirect('/absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen belum ada!', session('error'));
    }
}
