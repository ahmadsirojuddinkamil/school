<?php

namespace Modules\Ppdb\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class PpdbFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Ppdb\Entities\Ppdb::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID'); // Menggunakan bahasa Indonesia

        return [
            'uuid' => Uuid::uuid4()->toString(),
            'nama_lengkap' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'nisn' => $faker->numberBetween(1234524534, 4332498964),
            'asal_sekolah' => $faker->city,
            'alamat' => $faker->city,
            'telpon_siswa' => '0'.$faker->unique()->numberBetween(821, 899).$faker->randomNumber(6),
            'jenis_kelamin' => $faker->randomElement(['laki-laki', 'perempuan']),
            'tempat_lahir' => $faker->city,
            'tanggal_lahir' => $faker->dateTimeBetween('-21 years', 'now')->format('Y-m-d'),
            'tahun_daftar' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y'),
            'jurusan' => $faker->randomElement(['teknik komputer jaringan', 'rekayasa perangkat lunak', 'multimedia']),
            'nama_ayah' => $faker->name('male'),
            'nama_ibu' => $faker->name('female'),
            'telpon_orang_tua' => '0'.$faker->unique()->numberBetween(821, 899).$faker->randomNumber(6),
            'bukti_pendaftaran' => $faker->unique()->regexify('[A-Z0-9]{10}'),
        ];
    }
}