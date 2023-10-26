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
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);
        Ppdb::factory()->create();

        $response = $this->get('/data-ppdb/tahun-daftar');
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::layouts.admin.year');
        $response->assertSeeText('Data PPDB');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

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
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar');
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_year_success_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/' . $ppdb->tahun_daftar);
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::layouts.admin.show_year');
        $response->assertSeeText('PPDB tahun');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataPpdb');
        $getDataPpdb = $response->original->getData()['getDataPpdb'];
        $this->assertNotNull($getDataPpdb);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $getDataPpdb);
        $this->assertInstanceOf(\Modules\Ppdb\Entities\Ppdb::class, $getDataPpdb->first());

        $response->assertViewHas('totalDataPpdb');
        $totalDataPpdb = $response->original->getData()['totalDataPpdb'];
        $this->assertNotNull($totalDataPpdb);
        $this->assertIsInt($totalDataPpdb);

        $response->assertViewHas('saveYearFromRoute');
        $saveYearFromRoute = $response->original->getData()['saveYearFromRoute'];
        $this->assertNotNull($saveYearFromRoute);
        $this->assertIsString($saveYearFromRoute);
    }

    public function test_ppdb_show_data_year_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/' . $ppdb->tahun_daftar);
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_year_failed_because_not_year(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/tahun-daftar/tahun');
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_year_failed_because_year_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/year/500000');
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_user_success_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/' . $ppdb->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::layouts.admin.show');
        $response->assertSeeText('Peserta PPDB Details');

        $response->assertViewHas('dataUserAuth');
        $dataUserAuth = $response->original->getData()['dataUserAuth'];
        $this->assertIsArray($dataUserAuth);
        $this->assertNotEmpty($dataUserAuth);

        $response->assertViewHas('getDataUserPpdb');
        $getDataUserPpdb = $response->original->getData()['getDataUserPpdb'];
        $this->assertNotNull($getDataUserPpdb);
        $this->assertInstanceOf(\Modules\Ppdb\Entities\Ppdb::class, $getDataUserPpdb);

        $response->assertViewHas('checkSiswaOrNot');
        $checkSiswaOrNot = $response->original->getData()['checkSiswaOrNot'];
        $this->assertFalse($checkSiswaOrNot);
    }

    public function test_ppdb_show_data_user_failed_because_not_role_admin(): void
    {
        $user = $this->roleService->createRoleAndUserSiswa();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/' . $ppdb->uuid);
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_user_failed_because_not_uuid(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/uuid');
        $response->assertStatus(404);
    }

    public function test_ppdb_show_data_user_failed_because_data_not_found(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        Ppdb::factory()->create();
        $response = $this->get('/data-ppdb/3343ecac-b140-4e31-889f-a2ecd31e9168');
        $response->assertStatus(404);
    }
}
