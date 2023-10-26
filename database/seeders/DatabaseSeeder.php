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

        $faker = \Faker\Factory::create('id_ID');

        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tata_usaha']);
        Role::create(['name' => 'satpam']);
        Role::create(['name' => 'pramukantor']);
        Role::create(['name' => 'kepala_sekolah']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'orang_tua_siswa']);
        Role::create(['name' => 'siswa']);

        $superAdmin = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'super_admin@example.com',
            'password' => '12345678',
        ]);

        $admin = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'admin@example.com',
            'password' => '12345678',
        ]);

        $tataUsaha = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'tata_usaha@example.com',
            'password' => '12345678',
        ]);

        $satpam = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'satpam@example.com',
            'password' => '12345678',
        ]);

        $pramukantor = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'pramukantor@example.com',
            'password' => '12345678',
        ]);

        $kepalaSekolah = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'kepala_sekolah@example.com',
            'password' => '12345678',
        ]);

        $guru = \App\Models\User::factory()->create([
            'name' => 'tono sudarno',
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'guru@example.com',
            'password' => '12345678',
        ]);

        $orangTuaSiswa = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'orang_tua_siswa@example.com',
            'password' => '12345678',
        ]);

        $siswa = \App\Models\User::factory()->create([
            'name' => $faker->name,
            'uuid' => Uuid::uuid4()->toString(),
            'email' => 'siswa@example.com',
            'password' => '12345678',
        ]);

        $superAdmin->assignRole('super_admin');
        $admin->assignRole('admin');
        $tataUsaha->assignRole('tata_usaha');
        $satpam->assignRole('satpam');
        $pramukantor->assignRole('pramukantor');
        $kepalaSekolah->assignRole('kepala_sekolah');
        $guru->assignRole('guru');
        $orangTuaSiswa->assignRole('orang_tua_siswa');
        $siswa->assignRole('siswa');
    }
}
