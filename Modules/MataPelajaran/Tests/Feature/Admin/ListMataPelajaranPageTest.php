<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Tests\TestCase;

class ListMataPelajaranPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_list_mata_pelajaran_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        $mataPelajaran = MataPelajaran::factory()->create();

        $guru = Guru::factory()->create();
        $guru->update([
            'mata_pelajaran_uuid' => $mataPelajaran->uuid,
        ]);

        $response = $this->get('/data-mata-pelajaran');
        $response->assertStatus(200);
        $response->assertViewIs('matapelajaran::layouts.Admin.list');
        $response->assertSeeText('Data Mata Pelajaran');

        $response->assertViewHas('listMataPelajaran');
        $listMataPelajaran = $response->original->getData()['listMataPelajaran'];
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Collection::class, $listMataPelajaran);
        $this->assertGreaterThan(0, $listMataPelajaran->count());
    }

    public function test_list_mata_pelajaran_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        $response = $this->get('/data-mata-pelajaran');
        $response->assertStatus(404);
    }
}
