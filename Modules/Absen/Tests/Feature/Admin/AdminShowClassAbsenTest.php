<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class AdminShowClassAbsenTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_show_class_absen_page_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        $siswa = Siswa::AdminAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->count(20)->create();

        $response = $this->get('/absen-data/'.$siswa->kelas);
        $response->assertStatus(200);
        $response->assertViewIs('absen::pages.absen.siswa.show_class');
        $response->assertSeeText('Data Absen Siswa Kelas');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('saveClassFromObjectCall');
        $saveClassFromObjectCall = $response->original->getData()['saveClassFromObjectCall'];
        $this->assertNotEmpty($saveClassFromObjectCall);
        $this->assertTrue(in_array($saveClassFromObjectCall, ['10', '11', '12']));

        $response->assertViewHas('dataAbsen');
        $dataAbsen = $response->original->getData()['dataAbsen'];
        $this->assertIsArray($dataAbsen);
        $this->assertNotEmpty($dataAbsen);

        $response->assertViewHas('totalAbsen');
        $totalAbsen = $response->original->getData()['totalAbsen'];
        $this->assertIsInt($totalAbsen);
        $this->assertNotNull($totalAbsen);
    }

    public function test_show_class_absen_page_failed_because_not_class(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        $siswa = Siswa::SiswaGraduatedFactory()->create();
        $response = $this->get('/absen-data/'.$siswa->kelas);
        $response->assertStatus(302);
        $response->assertRedirect('/dashboard');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak di temukan!', session('error'));
    }

    public function test_show_class_absen_page_failed_because_class_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/absen-data/10');
        $response->assertStatus(302);
        $response->assertRedirect('/absen-data');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data absen tidak ditemukan!', session('error'));
    }

    public function test_show_class_absen_page_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $this->roleService->createUserSiswa();
        $siswa = Siswa::AdminAbsenFactory()->create();
        $response = $this->get('/absen-data/'.$siswa->kelas);
        $response->assertStatus(404);
    }
}
