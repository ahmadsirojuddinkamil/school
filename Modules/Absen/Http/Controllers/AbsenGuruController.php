<?php

namespace Modules\Absen\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\{DeleteDateAbsenGuruRequest, UpdateDateAbsenRequest, DeleteAbsenGuruRequest};
use Modules\Absen\Services\AbsenService;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Services\SiswaService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Modules\Absen\Exports\ExportAbsen;

class AbsenGuruController extends Controller
{
    protected $absenService;
    protected $siswaService;

    public function __construct(AbsenService $absenService, SiswaService $siswaService)
    {
        $this->absenService = $absenService;
        $this->siswaService = $siswaService;
    }

    public function listGuru()
    {
        $dataUserAuth = Session::get('userData');
        $listGuru = Guru::latest()->get();

        return view('absen::layouts.admin.guru.list', compact('dataUserAuth', 'listGuru'));
    }

    public function downloadZipAbsenGuruPdf()
    {
        $dataGuru = Guru::latest()->get();

        if ($dataGuru->isEmpty()) {
            return redirect('/data-absen/guru')->with('error', 'Data absen guru tidak ditemukan!');
        }

        foreach ($dataGuru as $guru) {
            $checkExistsAbsen = $guru->load('absens')->absens()->first();

            if ($checkExistsAbsen) {
                $dataAbsen = $guru->load('absens')->absens()->latest()->get();
                $listKeterangan = $this->absenService->totalKeterangan($dataAbsen);

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
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_absen_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/guru')->with('error', 'Laporan pdf absen tidak ada!');
        }

        $zipFileName = 'laporan_absen_pdf_guru.zip';
        $createZip = $this->absenService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-absen/guru')->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipAbsenGuruExcel()
    {
        // Star EXCEL
        $dataGuru = Guru::latest()->get();

        if ($dataGuru->isEmpty()) {
            return redirect('/data-absen/guru')->with('error', 'Data absen guru tidak ditemukan!');
        }

        foreach ($dataGuru as $guru) {
            $checkExistsAbsen = $guru->load('absens')->absens()->first();

            if ($checkExistsAbsen) {
                $absen = $guru->load('absens')->absens()->latest()->get();
                $fileName = 'laporan absen ' . $guru['name'] . '.xlsx';

                ExportExcel::store(new ExportAbsen($absen), $fileName, 'public');

                $sourcePath = storage_path('app/public/' . $fileName);
                $destinationPath = storage_path('app/public/document_laporan_excel_absen_guru/' . $fileName);

                File::move($sourcePath, $destinationPath);
            }
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_absen_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/guru')->with('error', 'Laporan excel absen tidak ada!');
        }

        $zipFileName = 'laporan_absen_excel_guru.zip';
        $createZip = $this->absenService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-absen/guru')->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function showAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataUserAuth = Session::get('userData');
        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-absen/guru')->with(['error' => 'Data guru tidak ditemukan!']);
        }

        $listAbsen = $dataGuru->load('absens')->absens()->latest()->get();

        if ($listAbsen->isEmpty()) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $listKehadiran = $this->absenService->totalKeterangan($listAbsen);

        return view('absen::layouts.admin.guru.report', compact('dataUserAuth', 'dataGuru', 'listKehadiran', 'listAbsen'));
    }

    public function deleteLaporanAbsen(DeleteAbsenGuruRequest $request)
    {
        $validateData = $request->validated();

        $dataGuru = Guru::where('uuid', $validateData['uuid'])->first();
        $dataAbsen = $dataGuru->load('absens')->absens()->latest()->get();

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

        if (!$dataGuru) {
            return redirect('/data-absen/guru')->with('error', 'Data guru tidak ditemukan!');
        }

        $dataAbsen = $dataGuru->load('absens')->absens()->latest()->get();

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/guru/' . $saveUuidFromCall)->with('error', 'Data absen belum ada!');
        }

        $listKeterangan = $this->absenService->totalKeterangan($dataAbsen);

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

        $dataAbsen = $dataGuru->load('absens')->absens()->latest()->get();

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

        $dataUserAuth = Session::get('userData');
        $dataAbsen = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataAbsen) {
            return redirect('/data-absen/guru')->with('error', 'Data absen tidak ditemukan!');
        }

        return view('absen::layouts.admin.guru.edit_date', compact('dataUserAuth', 'dataAbsen'));
    }

    public function updateDateAbsen($saveUuidFromCall, UpdateDateAbsenRequest $request)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/guru')->with(['error' => 'Data absen tidak valid!']);
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
