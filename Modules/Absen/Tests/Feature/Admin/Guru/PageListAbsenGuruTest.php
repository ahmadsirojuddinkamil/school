<?php

namespace Modules\Ppdb\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class PageListAbsenGuruTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_page_list_absen_guru_success_is_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $guru = Guru::factory()->create();

        $absen = Absen::BeforeAbsenFactory()->create();
        $absen->update([
            'guru_uuid' => $guru->uuid,
        ]);

        $response = $this->get('/data-absen/guru');
        $response->assertStatus(200);
        $response->assertViewIs('absen::layouts.admin.guru.list');
        $response->assertSeeText('Data Absen Guru');

        $response->assertViewHas('listGuru');
        $listGuru = $response->original->getData()['listGuru'];
        $this->assertNotNull($listGuru);
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $listGuru);
        $this->assertInstanceOf(\Modules\Guru\Entities\Guru::class, $listGuru->first());
    }

    public function test_page_list_absen_guru_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserGuru();
        session(['userData' => [$user, 'guru']]);
        $this->actingAs($user);

        $response = $this->get('/data-absen/guru');
        $response->assertStatus(404);
    }
}
