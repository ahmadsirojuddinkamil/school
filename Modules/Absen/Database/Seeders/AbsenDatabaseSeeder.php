<?php

namespace Modules\Absen\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Absen\Entities\Absen;

class AbsenDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Absen::AbsenSiswaFactory()->count(30)->create();

        // $this->call("OthersTableSeeder");
    }
}
