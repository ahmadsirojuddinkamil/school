<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class EditTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_edit_page_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/ppdb-data/'.$ppdb->uuid.'/edit');
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::pages.ppdb.edit');
        $response->assertSeeText('Edit PPDB');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataUserPpdb');
        $getDataUserPpdb = $response->original->getData()['getDataUserPpdb'];
        $this->assertNotNull($getDataUserPpdb);
        $this->assertInstanceOf(\Modules\Ppdb\Entities\Ppdb::class, $getDataUserPpdb);

        $response->assertViewHas('timeBox', function ($timeBox) {
            return is_array($timeBox);
        });
    }

    public function test_ppdb_edit_page_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/ppdb-data/'.$ppdb->uuid.'/edit');
        $response->assertStatus(404);
    }

    public function test_ppdb_edit_page_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/ppdb-data/uuid/edit');
        $response->assertStatus(404);
    }

    public function test_ppdb_edit_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/ppdb-data/3343ecac-b140-4e31-889f-a2ecd31e9168/uuid');
        $response->assertStatus(404);
    }

    public function test_ppdb_update_data_success(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->put('/ppdb-data/'.$ppdb->uuid, [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2017-11-18',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'tahun_daftar' => '2016',
            'bukti_pendaftaran_new' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'bukti_pendaftaran_old' => 'localstorage',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data ppdb berhasil di edit!', session('success'));
    }

    public function test_ppdb_update_data_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->put('/ppdb-data/'.$ppdb->uuid, [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2017-11-18',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'tahun_daftar' => '2016',
            'bukti_pendaftaran_new' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'bukti_pendaftaran_old' => 'localstorage',
        ]);
        $response->assertStatus(404);
    }

    public function test_ppdb_update_data_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->put('/ppdb-data/uuid', [
            'nama_lengkap' => 'name ppdb change',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1 change',
            'alamat' => 'jl. pancoran change',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta change',
            'tanggal_lahir' => '2017-11-18',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'tahun_daftar' => '2016',
            'bukti_pendaftaran_new' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'bukti_pendaftaran_old' => 'localstorage',
        ]);
        $response->assertStatus(404);
    }

    public function test_ppdb_update_data_failed_because_form_has_not_been(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->put('/ppdb-data/'.$ppdb->uuid, [
            'nama_lengkap' => '',
            'email' => '',
            'nisn' => '',
            'asal_sekolah' => '',
            'alamat' => '',
            'telpon_siswa' => '',
            'jenis_kelamin' => '',
            'tempat_lahir' => '',
            'tanggal_lahir' => '',
            'jurusan' => '',
            'nama_ayah' => '',
            'nama_ibu' => '',
            'telpon_orang_tua' => '',
            'tahun_daftar' => '',
            'bukti_pendaftaran_new' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
            'bukti_pendaftaran_old' => '',
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('errors'));
    }
}
