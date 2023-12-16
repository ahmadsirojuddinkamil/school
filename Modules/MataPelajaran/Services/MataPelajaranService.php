<?php

namespace Modules\MataPelajaran\Services;

use Illuminate\Support\Facades\File;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Ramsey\Uuid\Uuid;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\MataPelajaran\Exports\ExportExcelMapel;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class MataPelajaranService
{
    public function createMapel($validateData)
    {
        if (isset($validateData['materi_pdf'])) {
            $changeMateriPdfPath = $validateData['materi_pdf']->store('public/document_mata_pelajaran_pdf');
            $pathStorageMateriPdf = str_replace('public/', 'storage/', $changeMateriPdfPath);
        }

        if (isset($validateData['materi_ppt'])) {
            $changeMateriPptPath = $validateData['materi_ppt']->store('public/document_mata_pelajaran_ppt');
            $pathStorageMateriPpt = str_replace('public/', 'storage/', $changeMateriPptPath);
        }

        if (isset($validateData['foto'])) {
            $changeFotoPath = $validateData['foto']->store('public/document_foto_mata_pelajaran');
            $pathStorageFoto = str_replace('public/', 'storage/', $changeFotoPath);
        }

        $mataPelajaran = MataPelajaran::create([
            'uuid' => Uuid::uuid4(),
            'name' => $validateData['name'],
            'jam_awal' => $validateData['jam_awal'],
            'jam_akhir' => $validateData['jam_akhir'],
            'kelas' => $validateData['kelas'],
            'name_guru' => $validateData['name_guru'],
            'materi_pdf' => $pathStorageMateriPdf ?? null,
            'materi_ppt' => $pathStorageMateriPpt ?? null,
            'video' => $validateData['video'],
            'foto' => $pathStorageFoto ?? 'assets/dashboard/img/mapel.png',
        ]);

        if ($validateData['name_guru']) {
            Guru::updateOrCreate(
                ['name' => $validateData['name_guru']],
                ['mata_pelajaran_uuid' => $mataPelajaran->uuid]
            );
        }
    }

    public function updateMapel($validateData, $saveDataMapelFromCall)
    {
        if (isset($validateData['materi_pdf'])) {
            $changeFotoGuruPath = $validateData['materi_pdf']->store('public/document_mata_pelajaran_pdf');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoGuruPath);
            File::delete($saveDataMapelFromCall->materi_pdf);
        }

        if (isset($validateData['materi_ppt'])) {
            $changeFotoGuruPath = $validateData['materi_ppt']->store('public/document_mata_pelajaran_ppt');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoGuruPath);
            File::delete($saveDataMapelFromCall->materi_ppt);
        }

        if (isset($validateData['foto_new'])) {
            $changeFotoGuruPath = $validateData['foto_new']->store('public/document_foto_mata_pelajaran');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoGuruPath);

            if ($saveDataMapelFromCall->foto !== 'assets/dashboard/img/mapel.png') {
                File::delete($saveDataMapelFromCall->foto);
            }
        }

        $saveDataMapelFromCall->update([
            'jam_awal' => $validateData['jam_awal'],
            'jam_akhir' => $validateData['jam_akhir'],
            'kelas' => $validateData['kelas'],
            'name_guru' => $validateData['name_guru'],
            'materi_pdf' => $changePublicToStoragePath ?? $saveDataMapelFromCall['materi_pdf'],
            'materi_ppt' => $changePublicToStoragePath ?? $saveDataMapelFromCall['materi_ppt'],
            'video' => $validateData['video'],
            'foto' => $changePublicToStoragePath ?? $validateData['foto_old'],
        ]);
    }

    public function deleteMapel($saveDataMapelFromCall)
    {
        if ($saveDataMapelFromCall->materi_pdf) {
            File::delete($saveDataMapelFromCall->materi_pdf);
        }

        if ($saveDataMapelFromCall->materi_ppt) {
            File::delete($saveDataMapelFromCall->materi_ppt);
        }

        if ($saveDataMapelFromCall->foto !== 'assets/dashboard/img/mapel.png') {
            File::delete($saveDataMapelFromCall->foto);
        }

        Guru::where('mata_pelajaran_uuid', $saveDataMapelFromCall->uuid)->update(['mata_pelajaran_uuid' => null]);

        $saveDataMapelFromCall->delete();
    }

    public function createPdfAllMapel($saveDataMapelFromCall, $savePdfPathFromCall)
    {
        $dataAllMapel = $saveDataMapelFromCall;
        $pdfFolderPath = $savePdfPathFromCall;

        foreach ($dataAllMapel as $mapel) {
            $folderPath = $pdfFolderPath . '/' . $mapel->name;
            File::makeDirectory($folderPath, 0777, true);

            $pdf = DomPDF::loadView('matapelajaran::components.pdf_mata_pelajaran', [
                'dataMapel' => $mapel,
            ]);

            $fileName = 'Jadwal mapel ' . $mapel->name . '.pdf';
            $filePath = $folderPath . '/' . $fileName;
            $pdf->save($filePath);

            foreach (['materi_pdf', 'materi_ppt'] as $materiType) {
                $pathFileMateri = $this->getMateriMapel($mapel[$materiType] ?? null);
                if (File::exists($pathFileMateri)) {
                    $destinationFileZipPath = $folderPath . '/' . $materiType . '_' . $mapel->name . '.zip';
                    copy($pathFileMateri, $destinationFileZipPath);
                }
            }

            if ($pathYoutubeLink = $mapel['video'] ?? null) {
                $destinationFileTxtPath = $folderPath . '/Link youtube.txt';
                file_put_contents($destinationFileTxtPath, $pathYoutubeLink);
            }
        }
    }

    public function createExcelAllMapel($saveDataMapelFromCall, $saveExcelPathFromCall)
    {
        $dataAllMapel = $saveDataMapelFromCall;
        $excelFolderPath = $saveExcelPathFromCall;
        $randomStringNumber = $this->generateStringNumberRandom();

        foreach ($dataAllMapel as $mapel) {
            $folderPath = $excelFolderPath . '/' . $mapel->name;
            File::makeDirectory($folderPath, 0777, true);

            $fileExcelName = 'Jadwal mapel ' . $mapel->name . '_' . $randomStringNumber[0] . $randomStringNumber[1] . '.xlsx';
            ExportExcel::store(new ExportExcelMapel($mapel->uuid), $fileExcelName, 'public');

            $sourcePath = storage_path('app/public/' . $fileExcelName);

            $destinationFileName = str_replace(['_', $randomStringNumber[0], $randomStringNumber[1]], '', $fileExcelName);
            $destinationPath = $folderPath . '/' . $destinationFileName;

            File::move($sourcePath, $destinationPath);

            foreach (['materi_pdf', 'materi_ppt'] as $materiType) {
                $pathFileMateri = $this->getMateriMapel($mapel[$materiType] ?? null);
                if (File::exists($pathFileMateri)) {
                    $destinationFileZipPath = $folderPath . '/' . $materiType . '_' . $mapel->name . '.zip';
                    copy($pathFileMateri, $destinationFileZipPath);
                }
            }

            if ($pathYoutubeLink = $mapel['video'] ?? null) {
                $destinationFileTxtPath = $folderPath . '/Link youtube.txt';
                file_put_contents($destinationFileTxtPath, $pathYoutubeLink);
            }
        }
    }

    public function downloadZipAllMapelPdf($saveFolderPathFromCall, $saveNameFromCall)
    {
        $folderPath = $saveFolderPathFromCall;
        $zip = new ZipArchive;
        $zipFileName = 'data_' . $saveNameFromCall . '_all_mapel.zip';

        if (is_dir($folderPath) && $zip->open(public_path($zipFileName), ZipArchive::CREATE) === TRUE) {
            $filesToZip = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folderPath),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($filesToZip as $file) {
                if (!$file->isDir()) {
                    $relativePath = substr($file->getPathname(), strlen($folderPath) + 1);
                    $zip->addFile($file->getRealPath(), 'data ' . $saveNameFromCall . ' all mapel/' . $relativePath);
                }
            }

            $zip->close();

            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folderPath, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::CHILD_FIRST
            );

            foreach ($files as $file) {
                $file->isDir() ? rmdir($file->getRealPath()) : unlink($file->getRealPath());
            }

            rmdir($folderPath);

            $result = [
                response(),
                $zipFileName
            ];

            return $result;
        } else {
            return redirect('/data-mata-pelajaran')->with('error', 'Folder mata pelajaran tidak ditemukan!');
        }
    }

    public function createSchedulePdfMapel($saveDataMapelFromCall)
    {
        $dataMapel = $saveDataMapelFromCall;
        $pdf = DomPDF::loadView('matapelajaran::components.pdf_mata_pelajaran', [
            'dataMapel' => $dataMapel,
        ]);

        $randomStringNumber = $this->generateStringNumberRandom();
        $fileName = $dataMapel->name . '_' . $randomStringNumber[0] . $randomStringNumber[1] . '.pdf';
        $pdf->save(public_path('storage/document_mata_pelajaran_temporary/Jadwal mapel ' . $fileName));

        return 'Jadwal mapel ' . $fileName;
    }

    public function getMateriMapel($savePathMateriMapelFromCall)
    {
        return $savePathMateriMapelFromCall ? public_path($savePathMateriMapelFromCall) : null;
    }

    public function addFileToZip($zip, $filePath, $fileName, $zipFileName)
    {
        if ($filePath && file_exists($filePath)) {
            $relativePath = 'Data mapel ' . $zipFileName . '/' . $fileName;
            $zip->addFile($filePath, $relativePath);
        }
    }

    public function addStringToZip($zip, $filePath, $fileName, $zipFileName)
    {
        if ($filePath) {
            $fileName = 'Link youtube.txt';
            $relativePath = 'Data mapel ' . $zipFileName . '/' . $fileName;
            $zip->addFromString($relativePath, $filePath);
        }
    }

    public function generateStringNumberRandom()
    {
        return [
            Str::random(8),
            rand(1000, 9999)
        ];
    }
}
