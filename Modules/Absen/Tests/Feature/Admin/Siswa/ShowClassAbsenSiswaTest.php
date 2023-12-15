<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class ShowClassAbsenSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_show_class_absen_page_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::SiswaActiveFactory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'siswa_uuid' => $siswa->uuid,
        ]);

        $response = $this->get('/data-absen/siswa/' . $siswa->kelas);
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.siswa.show_class');
        $response->assertSeeText('Data Absen Siswa Kelas');

        $response->assertViewHas('saveClassFromCall');
        $saveClassFromCall = $response->original->getData()['saveClassFromCall'];
        $this->assertNotEmpty($saveClassFromCall);
        $this->assertTrue(in_array($saveClassFromCall, ['10', '11', '12']));

        $response->assertViewHas('listSiswa');
        $listSiswa = $response->original->getData()['listSiswa'];
        $this->assertNotNull($listSiswa);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $listSiswa);
        $this->assertInstanceOf(\Modules\Siswa\Entities\Siswa::class, $listSiswa->first());

        $response->assertViewHas('totalSiswa');
        $totalSiswa = $response->original->getData()['totalSiswa'];
        $this->assertNotNull($totalSiswa);
        $this->assertIsInt($totalSiswa);
    }

    public function test_show_class_absen_page_failed_because_not_class(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/kelas');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Kelas tidak di valid!', session('error'));
    }

    public function test_show_class_absen_page_failed_because_class_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/siswa/10');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen/siswa');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_show_class_absen_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/kelas/10');
        $response->assertStatus(404);
    }
}
