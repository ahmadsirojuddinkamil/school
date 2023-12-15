<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Ppdb\Entities\Ppdb;
use Tests\TestCase;

class PpdbTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_ppdb_list_year_page_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);
        Ppdb::factory()->create();

        $response = $this->get('/data-ppdb/tahun-daftar');
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::layouts.admin.year');
        $response->assertSeeText('Data PPDB');

        $response->assertViewHas('listYearPpdb');
        $listYearPpdb = $response->original->getData()['listYearPpdb'];
        $this->assertIsArray($listYearPpdb);
        $this->assertNotEmpty($listYearPpdb);

        $response->assertViewHas('timeBox', function ($timeBox) {
            return is_array($timeBox);
        });

        $response->assertViewHas('openOrClosePpdb');
    }

    public function test_ppdb_list_year_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar');
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_year_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/' . $ppdb->tahun_daftar);
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::layouts.admin.show_year');
        $response->assertSeeText('PPDB tahun');

        $response->assertViewHas('dataPpdb');
        $dataPpdb = $response->original->getData()['dataPpdb'];
        $this->assertNotNull($dataPpdb);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $dataPpdb);
        $this->assertInstanceOf(\Modules\Ppdb\Entities\Ppdb::class, $dataPpdb->first());

        $response->assertViewHas('totalPpdb');
        $totalPpdb = $response->original->getData()['totalPpdb'];
        $this->assertNotNull($totalPpdb);
        $this->assertIsInt($totalPpdb);

        $response->assertViewHas('saveYearFromCall');
        $saveYearFromCall = $response->original->getData()['saveYearFromCall'];
        $this->assertNotNull($saveYearFromCall);
        $this->assertIsString($saveYearFromCall);
    }

    public function test_ppdb_show_data_year_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/' . $ppdb->tahun_daftar);
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_year_failed_because_not_year(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/tahun');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_show_data_year_failed_because_year_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/5000');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data ppdb di tahun ini tidak ada!', session('error'));
    }

    public function test_ppdb_show_data_user_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/' . $ppdb->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::layouts.admin.show');
        $response->assertSeeText('Biodata Peserta Didik Baru');

        $response->assertViewHas('dataPpdb');
        $dataPpdb = $response->original->getData()['dataPpdb'];
        $this->assertNotNull($dataPpdb);
        $this->assertInstanceOf(\Modules\Ppdb\Entities\Ppdb::class, $dataPpdb);

        $response->assertViewHas('checkSiswaOrNot');
        $checkSiswaOrNot = $response->original->getData()['checkSiswaOrNot'];
        $this->assertFalse($checkSiswaOrNot);
    }

    public function test_ppdb_show_data_user_failed_because_not_role_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/' . $ppdb->uuid);
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_user_failed_because_not_uuid(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/uuid');
        $response->assertRedirect('/data-ppdb/tahun-daftar');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data tidak valid!', session('error'));
    }

    public function test_ppdb_show_data_user_failed_because_data_not_found(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/3343ecac-b140-4e31-889f-a2ecd31e9168');
        $response->assertRedirect('/data-ppdb/3343ecac-b140-4e31-889f-a2ecd31e9168');
        $this->assertTrue(session()->has('error'));
        $this->assertEquals('Data peserta ini tidak ada!', session('error'));
    }
}
