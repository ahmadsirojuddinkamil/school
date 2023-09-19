<?php

namespace Modules\Ppdb\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class OpenPpdbFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Ppdb\Entities\OpenPpdb::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'tanggal_mulai' => '10-05-2010',
            'tanggal_akhir' => '15-05-2010',
        ];
    }
}
