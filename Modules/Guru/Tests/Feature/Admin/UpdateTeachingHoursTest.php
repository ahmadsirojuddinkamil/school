<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class UpdateTeachingHoursTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_update_teaching_hours_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $now = Carbon::now();
        $newTime = $now->addMinutes(5);
        $newTimeString = $newTime->format('Y-m-d H:i:s');
        $response = $this->put('/data-guru/' . $guru->uuid . '/edit/teaching-hours', [
            'jam_mengajar_awal' => $newTimeString,
            'jam_mengajar_akhir' => $newTimeString,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Jam mengajar guru berhasil di edit!', session('success'));
    }

    public function test_update_teaching_hours_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $now = Carbon::now();
        $newTime = $now->addMinutes(5);
        $newTimeString = $newTime->format('Y-m-d H:i:s');
        $response = $this->put('/data-guru/' . $guru->uuid . '/edit/teaching-hours', [
            'jam_mengajar_awal' => $newTimeString,
            'jam_mengajar_akhir' => $newTimeString,
        ]);
        $response->assertStatus(404);
    }

    public function test_update_teaching_hours_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Guru::factory()->create();
        $now = Carbon::now();
        $newTime = $now->addMinutes(5);
        $newTimeString = $newTime->format('Y-m-d H:i:s');
        $response = $this->put('/data-guru/uuid/edit/teaching-hours', [
            'jam_mengajar_awal' => $newTimeString,
            'jam_mengajar_akhir' => $newTimeString,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak valid!', session('error'));
    }

    public function test_update_teaching_hours_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Guru::factory()->create();
        $now = Carbon::now();
        $newTime = $now->addMinutes(5);
        $newTimeString = $newTime->format('Y-m-d H:i:s');
        $response = $this->put('/data-guru/8347f28d-6afa-4091-b493-e5832546bd93/edit/teaching-hours', [
            'jam_mengajar_awal' => $newTimeString,
            'jam_mengajar_akhir' => $newTimeString,
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak ditemukan!', session('error'));
    }
}
