<?php

namespace Modules\MataPelajaran\Database\factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class MataPelajaranFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\MataPelajaran\Entities\MataPelajaran::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        $randomTime = Carbon::createFromTime(
            $faker->numberBetween(7, 8),   // Jam antara 7 dan 8
            $faker->numberBetween(0, 59),  // Menit antara 0 dan 59
            $faker->numberBetween(0, 59)   // Detik antara 0 dan 59
        );

        while ($randomTime->isWeekend()) {
            $randomTime = Carbon::createFromTime(
                $faker->numberBetween(7, 8),
                $faker->numberBetween(0, 59),
                $faker->numberBetween(0, 59)
            );
        }

        return [
            'uuid' => Uuid::uuid4(),
            'name' => $faker->randomElement(['ipa', 'ips', 'matematika', 'agama', 'teknologi', 'pertanian']),
            'jam_awal' => $randomTime->toTimeString(),
            'jam_akhir' => $randomTime->toTimeString(),
            'kelas' => $faker->randomElement(['10', '11', '12']),
            'name_guru' => null,
            'materi_pdf' => 'assets/dashboard/zip/mapel_pdf.zip',
            'materi_ppt' => 'assets/dashboard/zip/mapel_ppt.zip',
            'video' => null,
            'foto' => 'assets/dashboard/img/mapel.png',
        ];
    }
}
