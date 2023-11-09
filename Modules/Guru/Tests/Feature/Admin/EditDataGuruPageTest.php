<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class EditDataGuruPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_admin_edit_data_guru_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-guru/' . $guru->uuid . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('guru::layouts.edit');
        $response->assertSeeText('Biodata Guru');

        $response->assertViewHas('dataGuru', function ($dataGuru) {
            return $dataGuru !== null && $dataGuru instanceof \Modules\Guru\Entities\Guru;
        });
    }

    public function test_user_edit_data_guru_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $guru->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->get('/data-guru/' . $guru->uuid . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('guru::layouts.edit');
        $response->assertSeeText('Biodata Guru');

        $response->assertViewHas('dataGuru', function ($dataGuru) {
            return $dataGuru !== null && $dataGuru instanceof \Modules\Guru\Entities\Guru;
        });
    }

    public function test_edit_data_guru_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->get('/data-guru/' . $guru->uuid . '/edit');
        $response->assertStatus(404);
    }

    public function test_edit_data_guru_page_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-guru/uuid/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak valid!', session('error'));
    }

    public function test_edit_data_guru_page_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-guru/f2a7e279-f7fe-43a2-8a2c-489f3f60574d/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data guru tidak ditemukan!', session('error'));
    }
}
