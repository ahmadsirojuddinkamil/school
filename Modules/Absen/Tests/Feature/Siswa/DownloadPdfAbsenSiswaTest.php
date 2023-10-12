<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Tests\TestCase;

class DownloadPdfAbsenSiswaTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_download_pdf_laporan_absen_siswa_success(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->post('/laporan-absen/pdf', [
            'nisn' => $user->siswa->nisn,
        ]);
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_download_pdf_laporan_absen_siswa_failed_because_not_role(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        Absen::AbsenSiswaFactory()->create();
        $response = $this->post('/laporan-absen/pdf', [
            'nisn' => $user->siswa->nisn,
        ]);
        $response->assertStatus(404);
    }

    public function test_download_pdf_laporan_absen_siswa_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Siswa::SiswaAbsenFactory()->create();
        $response = $this->post('/laporan-absen/pdf', [
            'nisn' => $user->siswa->nisn,
        ]);
        $response->assertStatus(404);
    }
}
