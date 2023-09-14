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
        $response->assertViewHas('getPpdb');
        $response->assertViewHas('dataUserAuth');
        $response->assertViewHas('minDate');
        $response->assertViewHas('todayDate');
    }

    public function test_ppdb_failed_edit_page_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotUuid = $this->get('/ppdb-data/coding/edit');
        $responseParameterNotUuid->assertStatus(404);

        $responseNotFoundUuid = $this->get('/ppdb-data/3000/edit');
        $responseNotFoundUuid->assertStatus(404);
    }

    public function test_ppdb_success_update_data(): void
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

    public function test_ppdb_failed_update_data(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotUuid = $this->get('/ppdb-data/coding');
        $responseParameterNotUuid->assertStatus(404);
    }
}
