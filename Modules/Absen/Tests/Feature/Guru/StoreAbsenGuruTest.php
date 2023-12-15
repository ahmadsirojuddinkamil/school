<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class StoreAbsenGuruTest extends TestCase
{
    use RefreshDatabase;
    // use DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_store_absen_guru_success(): void
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

        $response = $this->post('/absen', [
            'status' => 'guru',
            'persetujuan' => 'setuju',
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/absen');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Anda berhasil melakukan absen!', session('success'));
    }

    public function test_store_absen_guru_failed_because_not_guru(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->post('/absen', [
            'status' => 'guru',
            'persetujuan' => 'setuju',
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(404);
    }

    public function test_store_absen_guru_failed_because_form_is_empty(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $response = $this->post('/absen', [
            'status' => '',
            'persetujuan' => '',
            'keterangan' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_store_absen_guru_failed_because_absen_today_exists(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $guru->update([
            'user_uuid' => $user->uuid,
        ]);

        $absen = Absen::AfterAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->post('/absen', [
            'status' => 'guru',
            'persetujuan' => 'setuju',
            'keterangan' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Anda sudah melakukan absen!', session('error'));
    }
}
