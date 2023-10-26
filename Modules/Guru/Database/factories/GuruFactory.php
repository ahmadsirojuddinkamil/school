<?php

namespace Modules\Guru\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class GuruFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Guru\Entities\Guru::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = \Faker\Factory::create('id_ID');

        $nip = $faker->optional()->numberBetween(483749561203947543, 856940350219345676);

        $mataPelajaran = [
            'Matematika',
            'Bahasa Inggris',
            'Bahasa Indonesia',
            'Fisika',
            'Kimia',
            'Biologi',
            'Sejarah',
            'Geografi',
            'Seni',
            'Olahraga',
        ];

        $agama = [
            'islam',
            'kristen',
            'katolik',
            'hindu',
            'buddha',
            'konghucu',
        ];

        $bank = [
            'BCA',
            'BRI',
            'BNI',
            'MANDIRI',
        ];

        return [
            'user_id' => null,
            'mata_pelajaran_id' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $faker->name,
            'nuptk' => $faker->regexify('[0-9]{16}'),
            'nip' => $faker->regexify('[0-9]{18}'),
            'tempat_lahir' => $faker->city,
            'tanggal_lahir' => $faker->dateTimeBetween('-50 years', 'now')->format('Y-m-d'),
            'agama' => $faker->randomElement($agama),
            'jenis_kelamin' => $faker->randomElement(['laki-laki', 'perempuan']),
            'status_perkawinan' => $faker->randomElement(['belum-menikah', 'sudah-menikah']),
            'jam_mengajar' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y-m-d H:i:s'),
            'pendidikan_terakhir' => $faker->randomElement(['s1', 's2']),
            'nama_tempat_pendidikan' => $faker->company,
            'ipk' => number_format($faker->randomFloat(2, 3.5, 4.0), 2),
            'tahun_lulus' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y-m-d'),
            'alamat_rumah' => $faker->city,
            'provinsi' => $faker->state,
            'kecamatan' => $faker->city,
            'kelurahan' => $faker->city,
            'kode_pos' => $faker->postcode,
            'email' => $faker->unique()->safeEmail,
            'no_telpon' => '0' . $faker->unique()->numberBetween(821, 899) . $faker->randomNumber(6),
            'tahun_daftar' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y-m-d'),
            'tahun_keluar' => $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y-m-d'),
            // 'tahun_keluar' => $faker->optional()->dateTimeBetween('2000-01-01', '2023-12-31') ? $faker->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y-m-d') : null,
            'foto' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => $faker->randomElement($bank),
            'nama_buku_rekening' => $faker->name,
            'no_rekening' => $faker->regexify('[0-9]{10,16}')
        ];
    }
}
