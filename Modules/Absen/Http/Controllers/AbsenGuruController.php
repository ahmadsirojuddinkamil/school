<?php

namespace Modules\Absen\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\{DeleteDateAbsenGuruRequest, UpdateDateAbsenRequest, DeleteAbsenGuruRequest};
use Modules\Absen\Services\AbsenService;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Services\SiswaService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Illuminate\Support\Facades\File;
use Modules\Absen\Exports\ExportAbsen;
use ZipArchive;

class AbsenGuruController extends Controller
{
    protected $userService;
    protected $absenService;
    protected $siswaService;

    public function __construct(UserService $userService, AbsenService $absenService, SiswaService $siswaService)
    {
        $this->userService = $userService;
        $this->absenService = $absenService;
        $this->siswaService = $siswaService;
    }

    public function listAbsen()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $listGuru = Guru::latest()->get();

        return view('absen::layouts.admin.guru.list', compact('dataUserAuth', 'listGuru'));
    }

    public function downloadZipAbsenGuruPdf()
    {
        $dataGuru = Guru::latest()->get();

        if ($dataGuru->isEmpty()) {
            return redirect('/data-absen/guru')->with('error', 'Data guru tidak ditemukan!');
        }

        foreach ($dataGuru as $guru) {
            $dataAbsen = $guru->absens()->latest()->get();
            $listKeterangan = $this->absenService->getTotalKeterangan($dataAbsen);

            $pdf = DomPDF::loadView('absen::layouts.admin.guru.pdf', [
                'listAbsen' => $dataAbsen,
                'totalAbsen' => $listKeterangan['totalAbsen'],
                'name' => $guru->name,
                'totalHadir' => $listKeterangan['totalHadir'],
                'totalSakit' => $listKeterangan['totalSakit'],
                'totalAcara' => $listKeterangan['totalAcara'],
                'totalMusibah' => $listKeterangan['totalMusibah'],
                'totalTidakHadir' => $listKeterangan['totalTidakHadir'],
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_absen_guru/' . $guru->name . '.pdf'));
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_absen_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/guru')->with('error', 'Laporan pdf absen tidak ada!');
        }

        $zipFileName = 'laporan_absen_pdf_guru.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-absen/guru')->with('error', 'Gagal membuat arsip ZIP!');
        }

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
        }

        $zip->close();

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipAbsenGuruExcel()
    {
        // Star EXCEL
        $dataGuru = Guru::latest()->get();

        if ($dataGuru->isEmpty()) {
            return redirect('/data-absen/guru')->with('error', 'Data guru tidak ditemukan!');
        }

        foreach ($dataGuru as $guru) {
            $absen = $guru->absens()->latest()->get();
            $fileName = 'laporan absen ' . $guru['name'] . '.xlsx';

            ExportExcel::store(new ExportAbsen($absen), $fileName, 'public');

            $sourcePath = storage_path('app/public/' . $fileName);
            $destinationPath = storage_path('app/public/document_laporan_excel_absen_guru/' . $fileName);

            File::move($sourcePath, $destinationPath);
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_absen_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/guru')->with('error', 'Laporan excel absen tidak ada!');
        }

        $zipFileName = 'laporan_absen_excel_guru.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-absen/guru')->with('error', 'Gagal membuat arsip ZIP!');
        }

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
        }

        $zip->close();

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function dataAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-absen/guru')->with(['error' => 'Data guru tidak ditemukan!']);
        }

        $listAbsen = $dataGuru->absens()->latest()->get();

        if ($listAbsen->isEmpty()) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $listKehadiran = $this->absenService->getTotalKeterangan($listAbsen);

        return view('absen::layouts.admin.guru.report', compact('dataUserAuth', 'dataGuru', 'listKehadiran', 'listAbsen'));
    }

    public function deleteAbsen(DeleteAbsenGuruRequest $request)
    {
        $validateData = $request->validated();

        $dataGuru = Guru::where('uuid', $validateData['uuid'])->first();
        $dataAbsen = $dataGuru->absens()->latest()->get();

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/guru')->with('error', 'Data laporan absen tidak ditemukan!');
        }

        foreach ($dataAbsen as $absen) {
            $absen->delete();
        }

        return redirect('/data-absen/guru')->with('success', 'Data laporan absen berhasil dihapus!');
    }

    public function downloadPdfAbsenGuru($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();
        $dataAbsen = $dataGuru->absens()->latest()->get();
        $listKeterangan = $this->absenService->getTotalKeterangan($dataAbsen);

        if (empty($listKeterangan)) {
            return redirect('/data-absen/guru/' . $saveUuidFromCall)->with('error', 'Data absen belum ada!');
        }

        $pdf = DomPDF::loadView('absen::layouts.admin.guru.pdf', [
            'listAbsen' => $dataAbsen,
            'totalAbsen' => $listKeterangan['totalAbsen'],
            'name' => $dataGuru->name,
            'totalHadir' => $listKeterangan['totalHadir'],
            'totalSakit' => $listKeterangan['totalSakit'],
            'totalAcara' => $listKeterangan['totalAcara'],
            'totalMusibah' => $listKeterangan['totalMusibah'],
            'totalTidakHadir' => $listKeterangan['totalTidakHadir'],
        ]);

        return $pdf->download('laporan pdf absen ' . $dataGuru->name . '.pdf');
    }

    public function downloadExcelAbsenGuru($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-absen/guru/' . $saveUuidFromCall)->with(['error' => 'Data guru tidak ditemukan!']);
        }

        $dataAbsen = $dataGuru->absens()->latest()->get();

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/guru/' . $saveUuidFromCall)->with(['error' => 'Data absen tidak ditemukan!']);
        }

        return ExportExcel::download(new ExportAbsen($dataAbsen), 'laporan absen ' . $dataGuru['name'] . '.xlsx');
    }

    public function editAbsen($saveUuidFromCall, $saveDateFromCaller)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak valid!']);
        }

        $timestamp = strtotime($saveDateFromCaller);

        if ($timestamp === false) {
            return redirect('/data-absen/guru/' . $saveUuidFromCall)->with('error', 'Data tanggal absen tidak valid!');
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataAbsen = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataAbsen) {
            return redirect('/data-absen/guru/' . $saveUuidFromCall)->with('error', 'Data absen tidak ditemukan!');
        }

        return view('absen::layouts.admin.guru.edit_date', compact('dataUserAuth', 'dataAbsen'));
    }

    public function updateDateAbsen($saveUuidFromCall, UpdateDateAbsenRequest $request)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen')->with(['error' => 'Data absen tidak valid!']);
        }

        $validateData = $request->validated();

        $dataAbsen = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataAbsen) {
            return redirect('/data-absen/guru')->with('error', 'Data absen tidak ditemukan!');
        }

        $dataAbsen->update([
            'keterangan' => $validateData['keterangan'],
        ]);

        return redirect('/data-absen/guru')->with('success', 'Berhasil update data absen!');
    }

    public function deleteDateAbsen(DeleteDateAbsenGuruRequest $request)
    {
        $validateData = $request->validated();

        $dataAbsen = Absen::where('uuid', $validateData['uuid'])->first();

        if (!$dataAbsen) {
            return redirect('/data-absen/guru')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $dataAbsen->delete();

        return redirect('/data-absen/guru')->with('success', 'Data tanggal absen berhasil dihapus!');
    }
}
