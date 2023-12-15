<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class UpdateMataPelajaranTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_update_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->put('/data-mata-pelajaran/' . $mataPelajaran->uuid . '/update', [
            'jam_awal' => '23:03:85',
            'jam_akhir' => '24:03:85',
            'kelas' => $mataPelajaran->kelas,
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Mata pelajaran ' . $mataPelajaran->name . ' berhasil di update!', session('success'));
    }

    public function test_update_mata_pelajaran_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->put('/data-mata-pelajaran/' . $mataPelajaran->uuid . '/update', [
            'jam_awal' => '23:03:85',
            'jam_akhir' => '24:03:85',
            'kelas' => $mataPelajaran->kelas,
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_mata_pelajaran_failed_because_form_is_empty(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->put('/data-mata-pelajaran/' . $mataPelajaran->uuid . '/update', [
            'jam_awal' => '',
            'jam_akhir' => '',
            'kelas' => $mataPelajaran->kelas,
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_mata_pelajaran_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->put('/data-mata-pelajaran/uuid/update', [
            'jam_awal' => '23:03:85',
            'jam_akhir' => '24:03:85',
            'kelas' => $mataPelajaran->kelas,
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak valid!', session('error'));
    }

    public function test_update_mata_pelajaran_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->put('/data-mata-pelajaran/591c9ffe-e271-4be9-bd61-95082ccbb9ae/update', [
            'jam_awal' => '23:03:85',
            'jam_akhir' => '24:03:85',
            'kelas' => '',
            'name_guru' => '',
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-mata-pelajaran');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data mata pelajaran tidak ditemukan!', session('error'));
    }
}
