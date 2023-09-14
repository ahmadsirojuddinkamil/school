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

        Siswa::factory(100)->create();

        // $this->call("OthersTableSeeder");
    }
}
