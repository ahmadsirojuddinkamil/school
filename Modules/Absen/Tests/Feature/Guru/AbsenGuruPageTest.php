<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class AbsenGuruPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_absen_guru_page_before_absen_success_is_displayed(): void
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

        $response = $this->get('/absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.create_absen');

        // false
        $response->assertViewHas('checkAbsenOrNot');
        $checkAbsenOrNot = $response->original->getData()['checkAbsenOrNot'];
        $this->assertFalse($checkAbsenOrNot);
    }

    public function test_absen_guru_page_after_absen_success_is_displayed(): void
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

        $response = $this->get('/absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.create_absen');
        $response->assertSeeText('Absen');

        // true
        $response->assertViewHas('checkAbsenOrNot');
        $checkAbsenOrNot = $response->original->getData()['checkAbsenOrNot'];
        $this->assertTrue($checkAbsenOrNot);
    }

    public function test_absen_guru_page_failed_because_not_siswa(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/absen');
        $response->assertStatus(404);
    }
}
