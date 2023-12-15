<?php

namespace Modules\MataPelajaran\Tests\Feature;

use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Guru\Entities\Guru;
use Tests\TestCase;

class CreateMataPelajaranPageTest extends TestCase
{
    use RefreshDatabase;

    protected $roleService;

    public function setUp(): void
    {
        parent::setUp();
        $this->roleService = new UserService();
    }

    public function test_create_mata_pelajaran_page_success_displayed(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserAdmin();
        session(['userData' => [$user, 'admin']]);
        $this->actingAs($user);

        Guru::factory()->create();

        $response = $this->get('/data-mata-pelajaran/create');
        $response->assertStatus(200);
        $response->assertViewIs('matapelajaran::layouts.Admin.create');
        $response->assertSeeText('Tambah Mata Pelajaran');

        $response->assertViewHas('listNameGuru');
        $listNameGuru = $response->original->getData()['listNameGuru'];
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $listNameGuru);
        $this->assertGreaterThan(0, $listNameGuru->count());
    }

    public function test_create_mata_pelajaran_page_failed_because_not_admin(): void
    {
        $this->roleService->createRole();
        $user = $this->roleService->createUserSiswa();
        $this->actingAs($user);

        Guru::factory()->create();

        $response = $this->get('/data-mata-pelajaran/create');
        $response->assertStatus(404);
    }
}
