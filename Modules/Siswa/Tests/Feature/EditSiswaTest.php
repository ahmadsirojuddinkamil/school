<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class EditSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_edit_siswa_page_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);
        $siswa = Siswa::factory()->create();

        $response = $this->get('/siswa-data/' . $siswa->uuid . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('siswa::pages.siswa.edit');
        $response->assertSeeText('Edit data siswa');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataSiswa', function ($getDataSiswa) {
            return $getDataSiswa !== null && $getDataSiswa instanceof \Modules\Siswa\Entities\Siswa;
        });

        $response->assertViewHas('timeBox', function ($timeBox) {
            return is_array($timeBox);
        });
    }

    public function test_edit_siswa_page_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::factory()->create();
        $response = $this->get('/siswa-data/' . $siswa->uuid . '/edit');
        $response->assertStatus(404);
    }

    public function test_edit_siswa_page_failed_displayed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotUuid = $this->get('/siswa-data/uuid/edit');
        $responseParameterNotUuid->assertStatus(302);
        $responseParameterNotUuid->assertRedirect('/siswa-data/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_edit_siswa_page_failed_displayed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::factory()->create();
        $responseParameterUuidNotFound = $this->get('/siswa-data/538e652b-81bf-4d2c-94ac-194ffdf515c1/edit');
        $responseParameterUuidNotFound->assertStatus(302);
        $responseParameterUuidNotFound->assertRedirect('/siswa-data/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }

    public function test_update_siswa_graduated_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->put('/siswa-data/' . $siswa->uuid, [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'kelas' => 'lulus',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2001-11-18',
            'tahun_daftar' => '2017',
            'tahun_lulus' => '2020',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/siswa-data/status');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data siswa berhasil di edit!', session('success'));
    }

    public function test_update_siswa_graduated_failed_because_form_has_not_been(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->put('/siswa-data/' . $siswa->uuid, [
            'nama_lengkap' => '',
            'email' => '',
            'nisn' => '',
            'asal_sekolah' => '',
            'kelas' => '',
            'alamat' => '',
            'telpon_siswa' => '',
            'jenis_kelamin' => '',
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'tahun_daftar' => '',
            'tahun_lulus' => '',
            'jurusan' => '',
            'nama_ayah' => '',
            'nama_ibu' => '',
            'telpon_orang_tua' => '',
            'foto_old' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_siswa_graduated_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $siswa = Siswa::siswaGraduatedFactory()->create();
        $response = $this->put('/siswa-data/' . $siswa->uuid, [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'kelas' => 'lulus',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2001-11-18',
            'tahun_daftar' => '2017',
            'tahun_lulus' => '2020',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_siswa_graduated_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::siswaGraduatedFactory()->create();
        $response = $this->put('/siswa-data/uuid', [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'kelas' => 'lulus',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2001-11-18',
            'tahun_daftar' => '2017',
            'tahun_lulus' => '2020',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
        ]);
        $response->assertStatus(404);
    }

    public function test_update_siswa_graduated_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Siswa::siswaGraduatedFactory()->create();
        $response = $this->put('/siswa-data/03fd305a-7bb4-4118-bab9-656a942a2edf', [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'kelas' => 'lulus',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2001-11-18',
            'tahun_daftar' => '2017',
            'tahun_lulus' => '2020',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
        ]);
        $response->assertStatus(404);
    }
}
