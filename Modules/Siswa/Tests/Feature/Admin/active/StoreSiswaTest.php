<?php

namespace Modules\Siswa\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class StoreSiswaTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_store_siswa_success(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->post('/data-siswa/create', [
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
            'foto' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-siswa/status/aktif/kelas');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data siswa berhasil ditambahkan!', session('success'));
    }

    public function test_store_siswa_failed_because_form_has_not_been(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $response = $this->post('/data-siswa/create', [
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
            'foto' => '',
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

    public function test_store_siswa_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $response = $this->post('/data-siswa/create', [
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
            'foto' => 'assets/dashboard/img/foto-siswa.png',
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

    public function test_store_siswa_failed_because_siswa_exists(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $this->post('/data-siswa/create', [
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
            'foto' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);

        $this->post('/data-siswa/create', [
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
            'foto' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'nama_bank' => 'bca',
            'nama_buku_rekening'  => 'rahmat',
            'no_rekening'  => '38382945',
            'nama_ayah'  => 'sumanto',
            'nama_ibu' => 'siska',
            'nama_wali'  => 'nurul',
            'telpon_orang_tua'  => 344334534,
        ]);
        $this->assertTrue(session()->has('errors'));
    }
}
