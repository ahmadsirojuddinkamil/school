<?php

namespace Modules\Guru\Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Ramsey\Uuid\Uuid;
use Tests\TestCase;

class StoreGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_create_guru_success(): void
    {
        $this->roleService->createRole();
        $userAdmin = $this->roleService->createUserSuperAdmin();
        $this->actingAs($userAdmin);

        $file = UploadedFile::fake()->image('sample.jpg');
        $user = User::factory()->create();

        $response = $this->post('/data-guru/create', [
            'user_uuid' => $user->uuid,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'zainal kurniawan',
            'nuptk' => '1953688939623496',
            'nip' => '341329740448564183',
            'tempat_lahir' => 'Palangka Raya',
            'tanggal_lahir' => '2018-01-13',
            'mata_pelajaran' => 'Geografi',
            'agama' => 'hindu',
            'jenis_kelamin' => 'laki-laki',
            'status_perkawinan' => 'sudah-menikah',
            'jam_mengajar_awal' => '2007-12-25 05:24:36',
            'jam_mengajar_akhir' => '2007-12-25 07:24:36',
            'pendidikan_terakhir' => 's1',
            'nama_tempat_pendidikan' => 'Yayasan Padmasari Gunawan (Persero) Tbk',
            'ipk' => '3.97',
            'tahun_lulus' => '2013',
            'alamat_rumah' => 'Banjarmasin',
            'provinsi' => 'Aceh',
            'kecamatan' => 'Banjarbaru',
            'kelurahan' => 'Jambi',
            'kode_pos' => '21312',
            'email' => 'atmaja26@example.org',
            'no_telpon' => '2342342343',
            'tahun_daftar' => '2332',
            'tahun_keluar' => '3243',
            'foto' => $file,
            'nama_bank' => 'BNI',
            'nama_buku_rekening' => 'Zelaya Kusmawati',
            'no_rekening' => '2342342',
        ]);
        $response->assertStatus(302);
        $response->assertRedirect('/data-guru');
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data guru berhasil dibuat!', session('success'));
    }

    public function test_create_guru_failed_because_not_super_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('sample.jpg');
        $user = User::factory()->create();

        $response = $this->post('/data-guru/create', [
            'user_uuid' => $user->uuid,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'zainal kurniawan',
            'nuptk' => '1953688939623496',
            'nip' => '341329740448564183',
            'tempat_lahir' => 'Palangka Raya',
            'tanggal_lahir' => '2018-01-13',
            'mata_pelajaran' => 'Geografi',
            'agama' => 'hindu',
            'jenis_kelamin' => 'laki-laki',
            'status_perkawinan' => 'sudah-menikah',
            'jam_mengajar_awal' => '2007-12-25 05:24:36',
            'jam_mengajar_akhir' => '2007-12-25 07:24:36',
            'pendidikan_terakhir' => 's1',
            'nama_tempat_pendidikan' => 'Yayasan Padmasari Gunawan (Persero) Tbk',
            'ipk' => '3.97',
            'tahun_lulus' => '2013',
            'alamat_rumah' => 'Banjarmasin',
            'provinsi' => 'Aceh',
            'kecamatan' => 'Banjarbaru',
            'kelurahan' => 'Jambi',
            'kode_pos' => '21312',
            'email' => 'atmaja26@example.org',
            'no_telpon' => '2342342343',
            'tahun_daftar' => '2332',
            'tahun_keluar' => '3243',
            'foto' => $file,
            'nama_bank' => 'BNI',
            'nama_buku_rekening' => 'Zelaya Kusmawati',
            'no_rekening' => '2342342',
        ]);
        $response->assertStatus(404);
    }

    public function test_create_guru_failed_because_data_already_exists(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $file = UploadedFile::fake()->image('sample.jpg');
        $user = User::factory()->create();

        $response = $this->post('/data-guru/create', [
            'user_uuid' => $user->uuid,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'zainal kurniawan',
            'nuptk' => '1953688939623496',
            'nip' => '341329740448564183',
            'tempat_lahir' => 'Palangka Raya',
            'tanggal_lahir' => '2018-01-13',
            'mata_pelajaran' => 'Geografi',
            'agama' => 'hindu',
            'jenis_kelamin' => 'laki-laki',
            'status_perkawinan' => 'sudah-menikah',
            'jam_mengajar_awal' => '2007-12-25 05:24:36',
            'jam_mengajar_akhir' => '2007-12-25 07:24:36',
            'pendidikan_terakhir' => 's1',
            'nama_tempat_pendidikan' => 'Yayasan Padmasari Gunawan (Persero) Tbk',
            'ipk' => '3.97',
            'tahun_lulus' => '2013',
            'alamat_rumah' => 'Banjarmasin',
            'provinsi' => 'Aceh',
            'kecamatan' => 'Banjarbaru',
            'kelurahan' => 'Jambi',
            'kode_pos' => '21312',
            'email' => 'atmaja26@example.org',
            'no_telpon' => '2342342343',
            'tahun_daftar' => '2332',
            'tahun_keluar' => '3243',
            'foto' => $file,
            'nama_bank' => 'BNI',
            'nama_buku_rekening' => 'Zelaya Kusmawati',
            'no_rekening' => '2342342',
        ]);

        $response = $this->post('/data-guru/create', [
            'user_uuid' => $user->uuid,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => 'zainal kurniawan',
            'nuptk' => '1953688939623496',
            'nip' => '341329740448564183',
            'tempat_lahir' => 'Palangka Raya',
            'tanggal_lahir' => '2018-01-13',
            'mata_pelajaran' => 'Geografi',
            'agama' => 'hindu',
            'jenis_kelamin' => 'laki-laki',
            'status_perkawinan' => 'sudah-menikah',
            'jam_mengajar_awal' => '2007-12-25 05:24:36',
            'jam_mengajar_akhir' => '2007-12-25 07:24:36',
            'pendidikan_terakhir' => 's1',
            'nama_tempat_pendidikan' => 'Yayasan Padmasari Gunawan (Persero) Tbk',
            'ipk' => '3.97',
            'tahun_lulus' => '2013',
            'alamat_rumah' => 'Banjarmasin',
            'provinsi' => 'Aceh',
            'kecamatan' => 'Banjarbaru',
            'kelurahan' => 'Jambi',
            'kode_pos' => '21312',
            'email' => 'atmaja26@example.org',
            'no_telpon' => '2342342343',
            'tahun_daftar' => '2332',
            'tahun_keluar' => '3243',
            'foto' => $file,
            'nama_bank' => 'BNI',
            'nama_buku_rekening' => 'Zelaya Kusmawati',
            'no_rekening' => '2342342',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }

    public function test_create_guru_failed_because_form_has_not_been(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSuperAdmin();
        $this->actingAs($user);

        $user = User::factory()->create();
        $response = $this->post('/data-guru/create', [
            'user_uuid' => $user->uuid,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => '',
            'nuptk' => '',
            'nip' => '',
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'mata_pelajaran' => '',
            'agama' => '',
            'jenis_kelamin' => '',
            'status_perkawinan' => '',
            'jam_mengajar' => '',
            'pendidikan_terakhir' => '',
            'nama_tempat_pendidikan' => '',
            'ipk' => '',
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
            'nama_buku_rekening' => '',
            'no_rekening' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
