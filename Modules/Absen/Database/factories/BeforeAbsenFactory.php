<?php

namespace Modules\Absen\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class BeforeAbsenFactory extends Factory
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
            'siswa_uuid' => null,
            'guru_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'status' => 12,
            'keterangan' => $faker->randomElement(['hadir', 'sakit', 'acara', 'musibah', 'tidak_hadir']),
            'persetujuan' => 'setuju',
            'created_at' => $randomDate->toDateTimeString(),
            'updated_at' => $randomDate->toDateTimeString(),
        ];
    }
}
