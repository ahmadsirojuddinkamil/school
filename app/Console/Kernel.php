<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Ramsey\Uuid\Uuid;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            $allNisn = Siswa::pluck('nisn')->toArray();
            $today = now();

            foreach ($allNisn as $nisn) {
                $latestAbsen = Absen::where('nisn', $nisn)
                    ->latest('created_at')
                    ->first();

                if ($latestAbsen) {
                    $latestAbsenDate = $latestAbsen->created_at->format('Y-m-d');

                    if ($latestAbsenDate !== $today->format('Y-m-d')) {
                        $findSiswa = Siswa::where('nisn', $nisn)->first();
                        Absen::create([
                            'uuid' => Uuid::uuid4()->toString(),
                            'name' => $findSiswa->nama_lengkap,
                            'nisn' => $nisn,
                            'status' => $findSiswa->kelas,
                            'persetujuan' => 'setuju',
                            'kehadiran' => 'tidak_hadir',
                        ]);
                    }
                }
            }
        })->timezone('Asia/Jakarta')
            ->dailyAt('14:01');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
