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
        $tanggalMulai = date('Y-m-d');
        $tanggalAkhir = date('Y-m-d', strtotime('+3 days'));

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'tanggal_mulai' => $tanggalMulai,
            'tanggal_akhir' => $tanggalAkhir,
        ];
    }
}
