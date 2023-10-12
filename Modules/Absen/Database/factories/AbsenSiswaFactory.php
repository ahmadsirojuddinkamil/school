<?php

namespace Modules\Absen\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class AbsenSiswaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Absen\Entities\Absen::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        $startDate = Carbon::create(2023, 1, 1, 7, 0, 0);
        $endDate = Carbon::create(2023, 12, 30, 8, 0, 0);

        do {
            $randomDate = Carbon::instance($faker->dateTimeBetween($startDate, $endDate));
        } while ($randomDate->isWeekend());

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $faker->name,
            'nisn' => 1234524534,
            // 'nisn' => $faker->numberBetween(1234524534, 4332498964),
            'status' => $faker->shuffle(['10', '11', '12'])[1],
            'persetujuan' => 'setuju',
            'kehadiran' => $faker->randomElement(['hadir', 'sakit', 'acara', 'musibah', 'tidak_hadir']),
            // 'created_at' => $randomDate->toDateTimeString(),
            // 'updated_at' => $randomDate->toDateTimeString(),
        ];
    }
}
