<?php

namespace Modules\Siswa\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class SiswaActiveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Siswa\Entities\Siswa::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        return [
            'user_uuid' => null,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),

            'name' => $faker->name,
            'nisn' => $faker->numberBetween(1234524534, 4332498964),
            'kelas' => $faker->shuffle(['10', '11', '12'])[1],
            'tempat_lahir' => $faker->city,
            'tanggal_lahir' => $faker->dateTimeBetween('-21 years', 'now')->format('Y-m-d'),
            'agama' => $faker->randomElement(['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu',]),
            'jenis_kelamin' => $faker->randomElement(['laki-laki', 'perempuan']),
            'asal_sekolah' => $faker->city,
            'nem' => number_format($faker->randomFloat(2, 3.5, 4.0), 2),
            'tahun_lulus' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y'),
            'alamat_rumah' => $faker->city,
            'provinsi' => $faker->state,
            'kecamatan' => $faker->city,
            'kelurahan' => $faker->city,
            'kode_pos' => $faker->postcode,
            'email' => $faker->unique()->safeEmail,
            'no_telpon' => '0' . $faker->unique()->numberBetween(821, 899) . $faker->randomNumber(6),
            'tahun_daftar' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y'),
            'tahun_keluar' => null,
            'foto' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => $faker->randomElement(['BCA', 'BRI', 'BNI', 'MANDIRI']),
            'nama_buku_rekening' => $faker->name,
            'no_rekening' => $faker->regexify('[0-9]{10,16}'),
            'nama_ayah' => $faker->name,
            'nama_ibu' => $faker->name,
            'nama_wali' => $faker->name,
            'telpon_orang_tua' => '0' . $faker->unique()->numberBetween(821, 899) . $faker->randomNumber(6),
        ];
    }
}
