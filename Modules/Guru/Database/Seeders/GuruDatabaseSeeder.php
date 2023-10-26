<?php

namespace Modules\Guru\Database\Seeders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Modules\Guru\Database\factories\GuruFactory;
use Modules\Guru\Entities\Guru;

class GuruDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Guru::factory(5)->create();

        // $this->call("OthersTableSeeder");
    }
}
