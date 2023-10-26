<?php

namespace Modules\Absen\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\DB;
use Modules\Absen\Entities\Absen;
use Ramsey\Uuid\Uuid;

class AbsenService
{
    public function create($validateData, $saveIdFromCaller)
    {
        DB::beginTransaction();

        Absen::create([
            'siswa_id' => in_array($validateData['status'], ['10', '11', '12']) ? $saveIdFromCaller : null,
            'guru_id' => $validateData['status'] == 'guru' ? $saveIdFromCaller : null,
            'uuid' => Uuid::uuid4()->toString(),
            'status' => $validateData['status'],
            'keterangan' => $validateData['keterangan'],
            'persetujuan' => $validateData['persetujuan'],
        ]);

        DB::commit();
    }

    public function getTotalKeterangan($saveListAbsenFromCall)
    {
        $listKeterangan = $saveListAbsenFromCall->pluck('keterangan')->map(function ($keterangan) {
            return in_array($keterangan, ['hadir', 'sakit', 'acara', 'musibah', 'tidak_hadir']) ? $keterangan : null;
        });

        $counts = $listKeterangan->countBy();

        $totalAbsen = ($counts['hadir'] ?? 0) + ($counts['sakit'] ?? 0) + ($counts['acara'] ?? 0) + ($counts['musibah'] ?? 0);
        $totalHadir = $counts['hadir'] ?? 0;
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

    public function getListLaporanAbsenSiswa($saveDataSiswaFromCall)
    {
        $listLaporanAbsen = [];

        foreach ($saveDataSiswaFromCall as $siswa) {
            $dataAbsen = $siswa->absens()->latest()->get();

            if ($dataAbsen->isNotEmpty()) {
                $namaSiswa = $siswa->nama_lengkap;

                $totalAbsen = 0;

                foreach ($dataAbsen as $absen) {
                    if ($absen->keterangan === 'hadir' || $absen->keterangan === 'sakit' || $absen->keterangan === 'acara' || $absen->keterangan === 'musibah') {
                        $totalAbsen++;
                    }
                }

                $totalHadir = 0;
                $totalSakit = 0;
                $totalAcara = 0;
                $totalMusibah = 0;
                $totalTidakHadir = 0;

                foreach ($dataAbsen as $absen) {
                    if ($absen->keterangan === 'hadir') {
                        $totalHadir++;
                    } elseif ($absen->keterangan === 'sakit') {
                        $totalSakit++;
                    } elseif ($absen->keterangan === 'acara') {
                        $totalAcara++;
                    } elseif ($absen->keterangan === 'musibah') {
                        $totalMusibah++;
                    } elseif ($absen->keterangan === 'tidak_hadir') {
                        $totalTidakHadir++;
                    }
                }

                $listLaporanAbsen[$namaSiswa] = [
                    'dataAbsen' => $dataAbsen,
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

    public function createPdfLaporanAbsenSiswa($saveDataAbsenFromCall, $saveClassFromCall)
    {
        foreach ($saveDataAbsenFromCall as $name => $laporan) {
            $pdf = DomPDF::loadView('absen::layouts.admin.siswa.pdf', [
                'name' => $name,
                'totalAbsen' => $laporan['totalAbsen'],
                'dataAbsen' => $laporan['dataAbsen'],
                'totalHadir' => $laporan['totalHadir'],
                'totalSakit' => $laporan['totalSakit'],
                'totalAcara' => $laporan['totalAcara'],
                'totalMusibah' => $laporan['totalMusibah'],
                'totalTidakHadir' => $laporan['totalTidakHadir'],
            ]);

            $pdf->save(public_path('storage/document_laporan_absen_kelas_' . $saveClassFromCall . '/' . $name . '.pdf'));
        }

        return 'success';
    }

    public function checkUuidOrNot($saveUuidFromCaller)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return redirect('/data-absen')->with(['error' => 'Data siswa tidak ditemukan!']);
        }
    }
}
