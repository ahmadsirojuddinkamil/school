<?php

namespace Modules\Absen\Services;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\DB;
use Modules\Absen\Entities\Absen;
use Ramsey\Uuid\Uuid;
use ZipArchive;

class AbsenService
{
    public function create($validateData, $saveUuidFromCall)
    {
        DB::beginTransaction();

        Absen::create([
            'siswa_uuid' => in_array($validateData['status'], ['10', '11', '12']) ? $saveUuidFromCall : null,
            'guru_uuid' => $validateData['status'] == 'guru' ? $saveUuidFromCall : null,
            'uuid' => Uuid::uuid4(),
            'status' => $validateData['status'],
            'keterangan' => $validateData['keterangan'],
            'persetujuan' => $validateData['persetujuan'],
        ]);

        DB::commit();
    }

    public function totalKeterangan($saveListAbsenFromCall)
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

    public function createZip($saveFileNameFromCall, $saveFolderPathFromCall)
    {
        $zip = new ZipArchive;
        $zipFileName = $saveFileNameFromCall;
        $folderPath = $saveFolderPathFromCall;

        $zip->open($zipFileName, ZipArchive::CREATE);
        $zip->addEmptyDir($saveFileNameFromCall);

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $relativePath = $saveFileNameFromCall . '/' . $fileName;
            $zip->addFile($file, $relativePath);
        }

        $zip->close();

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }
}
