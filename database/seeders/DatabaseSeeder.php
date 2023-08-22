<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Role::create(['name' => 'siswa']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kepala_sekolah']);

        $user = \App\Models\User::factory()->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => '12345678',
        ]);

        $user->assignRole('admin');
    }
}
