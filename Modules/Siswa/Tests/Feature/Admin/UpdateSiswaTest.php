<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class UpdateSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_admin_update_siswa_active_success(): void
    {
        $this->roleService->createRole();
        $userAdmin = $this->roleService->createUserAdmin();
        session(['userData' => [$userAdmin, 'admin']]);
        $this->actingAs($userAdmin);

        $siswa = Siswa::siswaActiveFactory()->create();
        $userSiswa = $this->roleService->createUserSiswa();
        $siswa->update([
            'user_uuid' => $userSiswa->uuid,
        ]);

        $response = $this->put('/data-siswa/' . $siswa->uuid, [
            'name' => 'peserta',
            'nisn' => '0064772666',
            'kelas' => '10',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2001-11-18',
            'agama' => 'islam',
            'jenis_kelamin' => 'laki-laki',
            'asal_sekolah' => 'smp',
            'nem' => '3',
            'tahun_lulus' => '2018',
            'alamat_rumah' => 'kampung pulo',
            'provinsi' => 'dki jakarta',
            'kecamatan' => 'pesanggrahan',
            'kelurahan' => 'bintaro',
            'kode_pos' => '43360',
            'email' => 'email@gmail.com',
            'no_telpon' => 8403943824,
            'tahun_daftar' => '2019',
            'tahun_keluar' => null,
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data siswa berhasil di edit!', session('success'));
    }

    public function test_user_update_siswa_active_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        session(['userData' => [$user, 'siswa']]);
        $this->actingAs($user);

        $siswa = Siswa::siswaActiveFactory()->create();
        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        $response = $this->put('/data-siswa/' . $siswa->uuid, [
            'name' => 'peserta',
            'nisn' => '0064772666',
            'kelas' => '10',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2001-11-18',
            'agama' => 'islam',
            'jenis_kelamin' => 'laki-laki',
            'asal_sekolah' => 'smp',
            'nem' => '3',
            'tahun_lulus' => '2018',
            'alamat_rumah' => 'kampung pulo',
            'provinsi' => 'dki jakarta',
            'kecamatan' => 'pesanggrahan',
            'kelurahan' => 'bintaro',
            'kode_pos' => '43360',
            'email' => 'email@gmail.com',
            'no_telpon' => 8403943824,
            'tahun_daftar' => '2019',
            'tahun_keluar' => null,
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas/' . $siswa->kelas . '/' . $siswa->uuid);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data anda berhasil di edit!', session('success'));
    }

    public function test_update_siswa_active_failed_because_form_has_not_been(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $siswa = Siswa::siswaActiveFactory()->create();
        $response = $this->put('/data-siswa/' . $siswa->uuid, [
            'name' => '',
            'nisn' => '',
            'kelas' => '',
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'agama' => '',
            'jenis_kelamin' => '',
            'asal_sekolah' => '',
            'nem' => '',
            'tahun_lulus' => '',
            'alamat_rumah' => '',
            'provinsi' => '',
            'kecamatan' => '',
            'kelurahan' => '',
            'kode_pos' => '',
            'email' => '',
            'no_telpon' => '',
            'tahun_daftar' => '',
            'tahun_keluar' => '',
            'foto_old' => '',
            'nama_bank' => '',
            'nama_buku_rekening'  => '',
            'no_rekening'  => '',
            'nama_ayah'  => '',
            'nama_ibu' => '',
            'nama_wali'  => '',
            'telpon_orang_tua'  => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_update_siswa_active_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        $this->actingAs($user);

        $siswa = Siswa::siswaActiveFactory()->create();
        $response = $this->put('/data-siswa/' . $siswa->uuid, [
            'name' => 'peserta',
            'nisn' => '0064772666',
            'kelas' => '10',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2001-11-18',
            'agama' => 'islam',
            'jenis_kelamin' => 'laki-laki',
            'asal_sekolah' => 'smp',
            'nem' => '3',
            'tahun_lulus' => '2018',
            'alamat_rumah' => 'kampung pulo',
            'provinsi' => 'dki jakarta',
            'kecamatan' => 'pesanggrahan',
            'kelurahan' => 'bintaro',
            'kode_pos' => '43360',
            'email' => 'email@gmail.com',
            'no_telpon' => 8403943824,
            'tahun_daftar' => '2019',
            'tahun_keluar' => null,
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $response->assertStatus(404);
    }

    public function test_update_siswa_active_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Siswa::siswaActiveFactory()->create();
        $response = $this->put('/data-siswa/uuid', [
            'name' => 'peserta',
            'nisn' => '0064772666',
            'kelas' => '10',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2001-11-18',
            'agama' => 'islam',
            'jenis_kelamin' => 'laki-laki',
            'asal_sekolah' => 'smp',
            'nem' => '3',
            'tahun_lulus' => '2018',
            'alamat_rumah' => 'kampung pulo',
            'provinsi' => 'dki jakarta',
            'kecamatan' => 'pesanggrahan',
            'kelurahan' => 'bintaro',
            'kode_pos' => '43360',
            'email' => 'email@gmail.com',
            'no_telpon' => 8403943824,
            'tahun_daftar' => '2019',
            'tahun_keluar' => null,
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak valid!', session('error'));
    }

    public function test_update_siswa_active_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Siswa::siswaActiveFactory()->create();
        $response = $this->put('/data-siswa/03fd305a-7bb4-4118-bab9-656a942a2edf', [
            'name' => 'peserta',
            'nisn' => '0064772666',
            'kelas' => '10',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2001-11-18',
            'agama' => 'islam',
            'jenis_kelamin' => 'laki-laki',
            'asal_sekolah' => 'smp',
            'nem' => '3',
            'tahun_lulus' => '2018',
            'alamat_rumah' => 'kampung pulo',
            'provinsi' => 'dki jakarta',
            'kecamatan' => 'pesanggrahan',
            'kelurahan' => 'bintaro',
            'kode_pos' => '43360',
            'email' => 'email@gmail.com',
            'no_telpon' => 8403943824,
            'tahun_daftar' => '2019',
            'tahun_keluar' => null,
            'foto_old' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $response->assertRedirect('/data-siswa/status');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data siswa tidak ditemukan!', session('error'));
    }
}
