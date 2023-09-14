<?php

namespace Modules\Siswa\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Ramsey\Uuid\Uuid;

class SiswaFactory extends Factory
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
        return [
            'uuid' => Uuid::uuid4()->toString(),
            'user_id' => null,
            'nama_lengkap' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'nisn' => fake()->numberBetween(1234524534, 4332498964),
            'asal_sekolah' => fake()->city(),
            'kelas' => fake()->numberBetween(10, 12),
            'alamat' => fake()->city(),
            'telpon_siswa' => fake()->phoneNumber,
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'tempat_lahir' => fake()->city,
            'tanggal_lahir' => fake()->unixTime,
            'tahun_daftar' => fake()->dateTimeBetween('2000-01-01', '2023-12-31')->format('Y'),
            'jurusan' => fake()->jobTitle(),
            'nama_ayah' => fake()->name('male'),
            'nama_ibu' => fake()->name('female'),
            'telpon_orang_tua' => fake()->phoneNumber,
            'foto' => null,
        ];
    }
}
