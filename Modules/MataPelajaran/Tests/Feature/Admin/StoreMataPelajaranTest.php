<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class StoreMataPelajaranTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_store_mata_pelajaran_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => 'matematika',
            'jam_awal' => '08:00:00',
            'jam_akhir' => '09:00:00',
            'kelas' => '11',
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
        $this->assertEquals('Mata pelajaran matematika berhasil ditambahkan!', session('success'));
    }

    public function test_store_mata_pelajaran_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => 'matematika',
            'jam_awal' => '08:00:00',
            'jam_akhir' => '09:00:00',
            'kelas' => '11',
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(404);
    }

    public function test_store_mata_pelajaran_failed_because_form_is_empty(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => '',
            'jam_awal' => '',
            'jam_akhir' => '',
            'kelas' => '',
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_store_mata_pelajaran_failed_because_name_exists(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => 'matematika',
            'jam_awal' => '08:00:00',
            'jam_akhir' => '09:00:00',
            'kelas' => '11',
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => 'matematika',
            'jam_awal' => '10:00:00',
            'jam_akhir' => '11:00:00',
            'kelas' => '11',
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(302);
        $errorName = session('errors')->get('name')[0];
        $this->assertEquals('The name has already been taken.', $errorName);
    }

    public function test_store_mata_pelajaran_failed_because_teaching_hours_exists(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => 'matematika',
            'jam_awal' => '08:00:00',
            'jam_akhir' => '09:00:00',
            'kelas' => '11',
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);

        $response = $this->post('/data-mata-pelajaran/store', [
            'uuid' => Uuid::uuid4(),
            'name' => 'ipa',
            'jam_awal' => '08:00:00',
            'jam_akhir' => '09:00:00',
            'kelas' => '11',
            'name_guru' => $guru->name,
            'materi_pdf' => null,
            'materi_ppt' => null,
            'video' => null,
            'foto_new' => null,
            'foto_old' => 'assets/dashboard/img/warning.png',
        ]);
        $response->assertStatus(302);

        $errorJamAwal = session('errors')->all()[0];
        $this->assertEquals('Jam mengajar awal sudah terisi!', $errorJamAwal);

        $errorJamAkhir = session('errors')->all()[1];
        $this->assertEquals('Jam mengajar akhir sudah terisi!', $errorJamAkhir);
    }
}
