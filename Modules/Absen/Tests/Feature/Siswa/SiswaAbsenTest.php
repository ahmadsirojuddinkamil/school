<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class SiswaAbsenTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_siswa_absen_page_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $response = $this->get('/absen');
        $response->assertStatus(200);
        $response->assertViewIs('absen::pages.absen.page');
        $response->assertSeeText('mendapatkan skor dan peringatan!');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('checkAbsenOrNot');
        $checkAbsenOrNot = $response->original->getData()['checkAbsenOrNot'];
        $this->assertNull($checkAbsenOrNot);
    }

    public function test_siswa_absen_page_failed_because_not_siswa(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/absen');
        $response->assertStatus(404);
    }

    public function test_siswa_absen_page_failed_because_already_absen(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/absen');
        $response->assertViewIs('absen::pages.absen.page');
        $response->assertSeeText('Anda sudah melakukan absen!');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('checkAbsenOrNot', function ($checkAbsenOrNot) {
            return $checkAbsenOrNot !== null && $checkAbsenOrNot instanceof \Modules\Absen\Entities\Absen;
        });
    }

    public function test_siswa_create_absen_success(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $response = $this->post('/absen', [
            'uuid' => '418aede1-aa7a-42b3-8a9d-083306880891',
            'name' => 'siswa',
            'nisn' => '1234567890',
            'status' => '11',
            'persetujuan' => 'setuju',
            'kehadiran' => 'hadir',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Anda berhasil melakukan absen!', session('success'));
    }

    public function test_siswa_create_absen_failed_because_not_siswa(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->post('/absen', [
            'uuid' => '418aede1-aa7a-42b3-8a9d-083306880891',
            'name' => 'siswa',
            'nisn' => '1234567890',
            'status' => '11',
            'persetujuan' => 'setuju',
            'kehadiran' => 'hadir',
        ]);
        $response->assertStatus(404);
    }

    public function test_siswa_create_absen_failed_because_already_absen(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $responseSuccess = $this->post('/absen', [
            'uuid' => '418aede1-aa7a-42b3-8a9d-083306880891',
            'name' => 'siswa',
            'nisn' => '1234567890',
            'status' => '11',
            'persetujuan' => 'setuju',
            'kehadiran' => 'hadir',
        ]);

        $responseError = $this->post('/absen', [
            'uuid' => '418aede1-aa7a-42b3-8a9d-083306880891',
            'name' => 'siswa',
            'nisn' => '1234567890',
            'status' => '11',
            'persetujuan' => 'setuju',
            'kehadiran' => 'hadir',
        ]);
        $responseSuccess->assertStatus(302);
        $responseError->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Anda sudah melakukan absen!', session('error'));
    }
}
