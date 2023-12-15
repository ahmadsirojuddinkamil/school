<?php

namespace Modules\MataPelajaran\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\MataPelajaran\Entities\MataPelajaran;

class MataPelajaranDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        // $this->call("OthersTableSeeder");

        MataPelajaran::factory(1)->create();
    }
}
