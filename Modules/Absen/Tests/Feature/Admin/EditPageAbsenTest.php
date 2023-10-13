<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Tests\TestCase;

class EditPageAbsenTest extends TestCase
{
    use RefreshDatabase, DatabaseMigrations;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_edit_absen_page_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/data-absen/' . $absen->uuid . '/' . $absen->created_at . '/edit');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.siswa.edit_date');
        $response->assertViewHas('dataUserAuth');
        $response->assertSeeText('Edit data absen');

        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataAbsen', function ($getDataAbsen) {
            return $getDataAbsen !== null && $getDataAbsen instanceof \Modules\Absen\Entities\Absen;
        });

        $saveDateFromCaller = $response->original->getData()['saveDateFromCaller'];
        $this->assertNotNull($saveDateFromCaller);
        $this->assertIsString($saveDateFromCaller);
        $this->assertMatchesRegularExpression('/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $saveDateFromCaller);
    }

    public function test_edit_absen_page_failed_because_not_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/data-absen/' . $absen->uuid . '/' . $absen->created_at . '/edit');
        $response->assertStatus(404);
    }

    public function test_edit_absen_page_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/data-absen/uuid/' . $absen->created_at . '/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }

    public function test_edit_absen_page_failed_because_not_date(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $absen = Absen::AbsenSiswaFactory()->create();
        $response = $this->get('/data-absen/' . $absen->uuid . '/date/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }

    public function test_edit_absen_page_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $response = $this->get('/data-absen/uuid/date/edit');
        $response->assertStatus(302);
        $response->assertRedirect('/data-absen');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tanggal absen tidak ditemukan!', session('error'));
    }
}
