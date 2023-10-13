<?php

namespace Modules\Siswa\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Siswa\Entities\Siswa;

class SiswaDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // Siswa::factory(50)->create();
        // Siswa::SiswaActiveFactory()->count(3)->create();
        Siswa::siswaGraduatedFactory()->count(15)->create();

        // $this->call("OthersTableSeeder");
    }
}
