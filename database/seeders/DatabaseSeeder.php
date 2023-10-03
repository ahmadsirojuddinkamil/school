<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Ramsey\Uuid\Uuid;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Role::create(['name' => 'siswa']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kepala_sekolah']);

        $admin = \App\Models\User::factory()->create([
            'name' => 'admin',
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'admin@example.com',
            'password' => '12345678',
        ]);

        $siswa = \App\Models\User::factory()->create([
            'name' => 'siswa',
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'siswa@example.com',
            'password' => '12345678',
        ]);

        $admin->assignRole('admin');
        $siswa->assignRole('siswa');
    }
}
