<?php

namespace Modules\Guru\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class UpdateBiodataGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_update_biodata_guru_success_user_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->put('/data-guru/' . $guru->uuid . '/edit', [
            'name' => 'zainal kurniawan',
            'nuptk' => $guru['nuptk'],
            'nip' => $guru['nip'],
            'tempat_lahir' => $guru['tempat_lahir'],
            'tanggal_lahir' => $guru['tanggal_lahir'],
            'mata_pelajaran' => $guru['mata_pelajaran'],
            'agama' => $guru['agama'],
            'jenis_kelamin' => $guru['jenis_kelamin'],
            'status_perkawinan' => $guru['status_perkawinan'],
            'jam_mengajar' => $guru['jam_mengajar'],
            'pendidikan_terakhir' => $guru['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $guru['nama_tempat_pendidikan'],
            'ipk' => $guru['ipk'],
            'tahun_lulus' => $guru['tahun_lulus'],
            'alamat_rumah' => $guru['alamat_rumah'],
            'provinsi' => $guru['provinsi'],
            'kecamatan' => $guru['kecamatan'],
            'kelurahan' => $guru['kelurahan'],
            'kode_pos' => $guru['kode_pos'],
            'email' => $guru['email'],
            'no_telpon' => $guru['no_telpon'],
            'tahun_daftar' => $guru['tahun_daftar'],
            'tahun_keluar' => $guru['tahun_keluar'],
            'foto_old' => $guru['foto'],
            'nama_bank' => $guru['nama_bank'],
            'nama_buku_rekening' => $guru['nama_buku_rekening'],
            'no_rekening' => $guru['no_rekening'],
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Biodata guru berhasil di edit!', session('success'));
    }

    public function test_update_biodata_guru_success_user_guru(): void
    {
        $user = $this->roleService->createRoleAndUserGuru();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->put('/data-guru/' . $guru->uuid . '/edit', [
            'name' => 'zainal kurniawan',
            'nuptk' => $guru['nuptk'],
            'nip' => $guru['nip'],
            'tempat_lahir' => $guru['tempat_lahir'],
            'tanggal_lahir' => $guru['tanggal_lahir'],
            'mata_pelajaran' => $guru['mata_pelajaran'],
            'agama' => $guru['agama'],
            'jenis_kelamin' => $guru['jenis_kelamin'],
            'status_perkawinan' => $guru['status_perkawinan'],
            'jam_mengajar' => $guru['jam_mengajar'],
            'pendidikan_terakhir' => $guru['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $guru['nama_tempat_pendidikan'],
            'ipk' => $guru['ipk'],
            'tahun_lulus' => $guru['tahun_lulus'],
            'alamat_rumah' => $guru['alamat_rumah'],
            'provinsi' => $guru['provinsi'],
            'kecamatan' => $guru['kecamatan'],
            'kelurahan' => $guru['kelurahan'],
            'kode_pos' => $guru['kode_pos'],
            'email' => $guru['email'],
            'no_telpon' => $guru['no_telpon'],
            'tahun_daftar' => $guru['tahun_daftar'],
            'tahun_keluar' => $guru['tahun_keluar'],
            'foto_old' => $guru['foto'],
            'nama_bank' => $guru['nama_bank'],
            'nama_buku_rekening' => $guru['nama_buku_rekening'],
            'no_rekening' => $guru['no_rekening'],
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru/' . $guru->uuid);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Biodata anda berhasil di edit!', session('success'));
    }

    public function test_update_biodata_guru_failed_because_not_super_admin(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->put('/data-guru/' . $guru->uuid . '/edit', [
            'name' => 'zainal kurniawan',
            'nuptk' => $guru['nuptk'],
            'nip' => $guru['nip'],
            'tempat_lahir' => $guru['tempat_lahir'],
            'tanggal_lahir' => $guru['tanggal_lahir'],
            'mata_pelajaran' => $guru['mata_pelajaran'],
            'agama' => $guru['agama'],
            'jenis_kelamin' => $guru['jenis_kelamin'],
            'status_perkawinan' => $guru['status_perkawinan'],
            'jam_mengajar' => $guru['jam_mengajar'],
            'pendidikan_terakhir' => $guru['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $guru['nama_tempat_pendidikan'],
            'ipk' => $guru['ipk'],
            'tahun_lulus' => $guru['tahun_lulus'],
            'alamat_rumah' => $guru['alamat_rumah'],
            'provinsi' => $guru['provinsi'],
            'kecamatan' => $guru['kecamatan'],
            'kelurahan' => $guru['kelurahan'],
            'kode_pos' => $guru['kode_pos'],
            'email' => $guru['email'],
            'no_telpon' => $guru['no_telpon'],
            'tahun_daftar' => $guru['tahun_daftar'],
            'tahun_keluar' => $guru['tahun_keluar'],
            'foto_old' => $guru['foto'],
            'nama_bank' => $guru['nama_bank'],
            'nama_buku_rekening' => $guru['nama_buku_rekening'],
            'no_rekening' => $guru['no_rekening'],
        ]);
        $response->assertStatus(404);
    }

    public function test_update_biodata_guru_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->put('/data-guru/uuid/edit', [
            'name' => 'zainal kurniawan',
            'nuptk' => $guru['nuptk'],
            'nip' => $guru['nip'],
            'tempat_lahir' => $guru['tempat_lahir'],
            'tanggal_lahir' => $guru['tanggal_lahir'],
            'mata_pelajaran' => $guru['mata_pelajaran'],
            'agama' => $guru['agama'],
            'jenis_kelamin' => $guru['jenis_kelamin'],
            'status_perkawinan' => $guru['status_perkawinan'],
            'jam_mengajar' => $guru['jam_mengajar'],
            'pendidikan_terakhir' => $guru['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $guru['nama_tempat_pendidikan'],
            'ipk' => $guru['ipk'],
            'tahun_lulus' => $guru['tahun_lulus'],
            'alamat_rumah' => $guru['alamat_rumah'],
            'provinsi' => $guru['provinsi'],
            'kecamatan' => $guru['kecamatan'],
            'kelurahan' => $guru['kelurahan'],
            'kode_pos' => $guru['kode_pos'],
            'email' => $guru['email'],
            'no_telpon' => $guru['no_telpon'],
            'tahun_daftar' => $guru['tahun_daftar'],
            'tahun_keluar' => $guru['tahun_keluar'],
            'foto_old' => $guru['foto'],
            'nama_bank' => $guru['nama_bank'],
            'nama_buku_rekening' => $guru['nama_buku_rekening'],
            'no_rekening' => $guru['no_rekening'],
        ]);
        $response->assertStatus(404);
    }

    public function test_update_biodata_guru_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSuperAdmin();
        $this->actingAs($user);

        $guru = Guru::factory()->create();
        $response = $this->put('/data-guru/d68ea3e5-02e5-4e7a-9167-703f391c0443/edit', [
            'name' => 'zainal kurniawan',
            'nuptk' => $guru['nuptk'],
            'nip' => $guru['nip'],
            'tempat_lahir' => $guru['tempat_lahir'],
            'tanggal_lahir' => $guru['tanggal_lahir'],
            'mata_pelajaran' => $guru['mata_pelajaran'],
            'agama' => $guru['agama'],
            'jenis_kelamin' => $guru['jenis_kelamin'],
            'status_perkawinan' => $guru['status_perkawinan'],
            'jam_mengajar' => $guru['jam_mengajar'],
            'pendidikan_terakhir' => $guru['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $guru['nama_tempat_pendidikan'],
            'ipk' => $guru['ipk'],
            'tahun_lulus' => $guru['tahun_lulus'],
            'alamat_rumah' => $guru['alamat_rumah'],
            'provinsi' => $guru['provinsi'],
            'kecamatan' => $guru['kecamatan'],
            'kelurahan' => $guru['kelurahan'],
            'kode_pos' => $guru['kode_pos'],
            'email' => $guru['email'],
            'no_telpon' => $guru['no_telpon'],
            'tahun_daftar' => $guru['tahun_daftar'],
            'tahun_keluar' => $guru['tahun_keluar'],
            'foto_old' => $guru['foto'],
            'nama_bank' => $guru['nama_bank'],
            'nama_buku_rekening' => $guru['nama_buku_rekening'],
            'no_rekening' => $guru['no_rekening'],
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Biodata guru gagal di edit!', session('error'));
    }
}
