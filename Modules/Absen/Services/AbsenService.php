<?php

namespace Modules\Absen\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\DB;
use Modules\Absen\Entities\Absen;
use Ramsey\Uuid\Uuid;

class AbsenService
{
    public function create($validateData)
    {
        DB::beginTransaction();

        Absen::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'nisn' => $validateData['nisn'],
            'status' => $validateData['status'],
            'persetujuan' => $validateData['persetujuan'],
            'kehadiran' => $validateData['kehadiran'],
        ]);

        DB::commit();
    }

    public function getTotalKehadiran($saveDataAbsenFromObjectCall)
    {
        $listKehadiran = $saveDataAbsenFromObjectCall->pluck('kehadiran')->map(function ($kehadiran) {
            return in_array($kehadiran, ['hadir', 'sakit', 'acara', 'musibah', 'tidak_hadir']) ? $kehadiran : null;
        });

        $counts = $listKehadiran->countBy();

        $totalAbsen = ($counts['hadir'] ?? 0) + ($counts['sakit'] ?? 0) + ($counts['acara'] ?? 0) + ($counts['musibah'] ?? 0);
        $totalHadir = $totalAbsen;
        $totalSakit = $counts['sakit'] ?? 0;
        $totalAcara = $counts['acara'] ?? 0;
        $totalMusibah = $counts['musibah'] ?? 0;
        $totalTidakHadir = $counts['tidak_hadir'] ?? 0;

        $result = [
            'totalAbsen' => $totalAbsen,
            'totalHadir' => $totalHadir,
            'totalSakit' => $totalSakit,
            'totalAcara' => $totalAcara,
            'totalMusibah' => $totalMusibah,
            'totalTidakHadir' => $totalTidakHadir,
        ];

        return $result;
    }

    public function getListLaporanAbsenSiswa($saveDataAbsenFromObjectCall)
    {
        $listLaporanAbsen = [];

        foreach ($saveDataAbsenFromObjectCall as $absen) {
            $getDataAbsen = Absen::where('nisn', $absen)->latest()->get();

            if ($getDataAbsen->isNotEmpty()) {
                $namaSiswa = $getDataAbsen[0]->name;

                $totalAbsen = 0;

                foreach ($getDataAbsen as $absen) {
                    if ($absen->kehadiran === 'hadir' || $absen->kehadiran === 'sakit' || $absen->kehadiran === 'acara' || $absen->kehadiran === 'musibah') {
                        $totalAbsen++;
                    }
                }

                $totalHadir = 0;
                $totalSakit = 0;
                $totalAcara = 0;
                $totalMusibah = 0;
                $totalTidakHadir = 0;

                foreach ($getDataAbsen as $absen) {
                    if ($absen->kehadiran === 'hadir') {
                        $totalHadir++;
                    } elseif ($absen->kehadiran === 'sakit') {
                        $totalSakit++;
                    } elseif ($absen->kehadiran === 'acara') {
                        $totalAcara++;
                    } elseif ($absen->kehadiran === 'musibah') {
                        $totalMusibah++;
                    } elseif ($absen->kehadiran === 'tidak_hadir') {
                        $totalTidakHadir++;
                    }
                }

                $listLaporanAbsen[$namaSiswa] = [
                    'dataAbsen' => $getDataAbsen,
                    'totalAbsen' => $totalAbsen,
                    'totalHadir' => $totalHadir,
                    'totalSakit' => $totalSakit,
                    'totalAcara' => $totalAcara,
                    'totalMusibah' => $totalMusibah,
                    'totalTidakHadir' => $totalTidakHadir,
                ];
            }
        }

        return $listLaporanAbsen;
    }

    public function createPdfLaporanAbsenSiswa($saveDataAbsenFromObjectCall, $saveClassFromCaller)
    {
        foreach ($saveDataAbsenFromObjectCall as $laporan) {
            $pdf = DomPDF::loadView('absen::layouts.absen.pdf_admin', [
                'name' => $laporan['dataAbsen'][0]->name,
                'totalAbsen' => $laporan['totalAbsen'],
                'dataAbsen' => $laporan['dataAbsen'],
                'totalHadir' => $laporan['totalHadir'],
                'totalSakit' => $laporan['totalSakit'],
                'totalAcara' => $laporan['totalAcara'],
                'totalMusibah' => $laporan['totalMusibah'],
                'totalTidakHadir' => $laporan['totalTidakHadir'],
            ]);

            $pdf->save(public_path('storage/document_laporan_absen_kelas_'.$saveClassFromCaller.'/'.$laporan['dataAbsen'][0]->name.'.pdf'));
        }

        return 'success';
    }

    public function checkUuidOrNot($saveUuidFromCaller)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return redirect('/absen-data')->with(['error' => 'Data siswa tidak ditemukan!']);
        }
    }
}
