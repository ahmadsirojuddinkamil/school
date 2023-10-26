<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;
use Ramsey\Uuid\Uuid;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Penjadwalan untuk Siswa
        $schedule->call(function () {
            $dataSiswa = Siswa::latest()->get();
            $today = now()->format('Y-m-d');

            foreach ($dataSiswa as $siswa) {
                $latestAbsen = $siswa->absens()->latest()->first()->updated_at->format('Y-m-d');

                if ($latestAbsen !== $today) {
                    Absen::create([
                        'siswa_id' => $siswa->id,
                        'guru_id' => null,
                        'uuid' => Uuid::uuid4()->toString(),
                        'status' => $siswa->kelas,
                        'keterangan' => 'tidak_hadir',
                        'persetujuan' => 'setuju',
                    ]);
                }
            }
        })->timezone('Asia/Jakarta')
            ->cron('01 14 * * *') // Menjadwalkan pada jam 14:13
            ->everyMinute() // Menjalankan setiap menit dalam jangka waktu 10 menit (hingga 14:23)
            ->skip(function () {
                return now()->format('i') >= 10; // Menghentikan penjadwalan setelah 14:23
            });
        // ->dailyAt('14:26');

        // Penjadwalan untuk Guru
        $schedule->call(function () {
            $dataGuru = Guru::latest()->get();
            $today = now()->format('Y-m-d');

            foreach ($dataGuru as $guru) {
                $latestAbsen = $guru->absens()->latest()->first()->updated_at->format('Y-m-d');

                if ($latestAbsen !== $today) {
                    Absen::create([
                        'siswa_id' => null,
                        'guru_id' => $guru->id,
                        'uuid' => Uuid::uuid4()->toString(),
                        'status' => 'guru',
                        'keterangan' => 'tidak_hadir',
                        'persetujuan' => 'setuju',
                    ]);
                }
            }
        })->timezone('Asia/Jakarta')
            ->cron('01 14 * * *')
            ->everyMinute()
            ->skip(function () {
                return now()->format('i') >= 10;
            });
        // ->dailyAt('14:26');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
