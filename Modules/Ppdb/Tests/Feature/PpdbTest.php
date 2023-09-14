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

        $response = $this->get('/ppdb-data/year');
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::pages.ppdb.year');
        $response->assertSeeText('Data PPDB');
        $response->assertViewHas('dataUserAuth');
        $response->assertViewHas('yearTotals');
    }

    public function test_ppdb_success_show_data_year_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/ppdb-data/year/'.$ppdb->tahun_daftar);
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::pages.ppdb.show_year');
        $response->assertSeeText('PPDB tahun');
        $response->assertViewHas('getDataPpdb');
        $response->assertViewHas('dataUserAuth');
        $response->assertViewHas('totalDataPpdb');
        $response->assertViewHas('saveYearFromRoute');
    }

    public function test_ppdb_failed_show_data_year_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotYear = $this->get('/ppdb-data/year/coding');
        $responseParameterNotYear->assertStatus(404);

        $responseNotFoundYear = $this->get('/ppdb-data/year/3000');
        $responseNotFoundYear->assertStatus(404);
    }

    public function test_ppdb_success_show_user_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $ppdb = Ppdb::factory()->create();
        $response = $this->get('/ppdb-data/'.$ppdb->uuid);
        $response->assertStatus(200);
        $response->assertViewIs('ppdb::pages.ppdb.show');
        $response->assertSeeText('Peserta PPDB Details');
        $response->assertViewHas('getDataUserPpdb');
        $response->assertViewHas('dataUserAuth');
        $response->assertViewHas('findSiswa');
    }

    public function test_ppdb_failed_show_user_is_displayed(): void
    {
        $user = $this->roleService->createRoleAndUserAdmin();
        $this->actingAs($user);

        $responseParameterNotUuid = $this->get('/ppdb-data/coding');
        $responseParameterNotUuid->assertStatus(404);

        $responseNotFoundUuid = $this->get('/ppdb-data/3000');
        $responseNotFoundUuid->assertStatus(404);
    }
}
