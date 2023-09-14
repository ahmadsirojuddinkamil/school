<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_register_page_is_displayed(): void
    {
        $response = $this->get('/ppdb');
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::pages.ppdb.register');
        $response->assertSeeText('Form Data PPDB');
        $response->assertViewHas('todayDate');
        $response->assertViewHas('minDate');
        $response->assertViewHas('dataUserAuth');
    }

    public function test_ppdb_register_success(): void
    {
        $response = $this->post('/ppdb', [
            'uuid' => '95d4c1b1-b856-41b5-90f7-62aa38673bd1',
            'nama_lengkap' => 'name ppdb',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1',
            'alamat' => 'jl. pancoran',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2017-11-18',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'tahun_daftar' => '2016',
            'bukti_pendaftaran' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
        ]);
        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));
        $this->assertEquals('Data ppdb anda berhasil dikirim! Tolong check email dalam 24 jam', session('success'));
    }

    public function test_ppdb_register_failed_due_to_existing_data(): void
    {
        $response = $this->post('/ppdb', [
            'uuid' => '95d4c1b1-b856-41b5-90f7-62aa38673bd1',
            'nama_lengkap' => 'name ppdb',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1',
            'alamat' => 'jl. pancoran',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2017-11-18',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'tahun_daftar' => '2016',
            'bukti_pendaftaran' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
        ]);

        $response->assertStatus(302);
        $this->assertTrue(session()->has('success'));

        $response = $this->post('/ppdb', [
            'uuid' => '95d4c1b1-b856-41b5-90f7-62aa38673bd1',
            'nama_lengkap' => 'name ppdb',
            'email' => 'ppdb@gmail.com',
            'nisn' => '0064772666',
            'asal_sekolah' => 'smp negeri 1',
            'alamat' => 'jl. pancoran',
            'telpon_siswa' => '085788223344',
            'jenis_kelamin' => 'laki-laki',
            'tempat_lahir' => 'jakarta',
            'tanggal_lahir' => '2017-11-18',
            'jurusan' => 'rekayasa perangkat lunak',
            'nama_ayah' => 'budi',
            'nama_ibu' => 'siti',
            'telpon_orang_tua' => '0895631100006',
            'tahun_daftar' => '2016',
            'bukti_pendaftaran' => UploadedFile::fake()->image('bukti_pendaftaran.jpg'),
        ]);

        $response->assertRedirect('/ppdb');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('NISN dan Email sudah terdaftar!', session('error'));
    }
}
