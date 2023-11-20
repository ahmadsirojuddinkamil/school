<?php

namespace Modules\Absen\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\{DeleteDateAbsenSiswaRequest, DeleteReportAbsenRequest, UpdateDateAbsenRequest};
use Modules\Absen\Services\AbsenService;
use Modules\Siswa\Entities\Siswa;
use Modules\Siswa\Services\SiswaService;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Absen\Exports\ExportAbsen;

class AbsenSiswaController extends Controller
{
    protected $absenService;
    protected $siswaService;

    public function __construct(AbsenService $absenService, SiswaService $siswaService)
    {
        $this->absenService = $absenService;
        $this->siswaService = $siswaService;
    }

    public function listClass()
    {
        $dataUserAuth = Session::get('userData');
        $listSiswaInClass = $this->siswaService->listSiswaInClass();

        return view('absen::layouts.admin.siswa.list_class', compact('dataUserAuth', 'listSiswaInClass'));
    }

    public function showClass($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-absen/siswa')->with(['error' => 'Kelas tidak di valid!']);
        }

        $dataUserAuth = Session::get('userData');

        $listSiswa = Siswa::where('kelas', $saveClassFromCall)
            ->latest()
            ->get();

        if ($listSiswa->isEmpty()) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $totalSiswa = count($listSiswa);

        return view('absen::layouts.admin.siswa.show_class', compact('dataUserAuth', 'saveClassFromCall', 'listSiswa', 'totalSiswa'));
    }

    public function showDataAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataUserAuth = Session::get('userData');
        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $listAbsen = $dataSiswa->load('absens')->absens()->latest()->get();

        if ($listAbsen->isEmpty()) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $listKehadiran = $this->absenService->totalKeterangan($listAbsen);

        return view('absen::layouts.admin.siswa.show_report', compact('dataUserAuth', 'dataSiswa', 'listKehadiran', 'listAbsen'));
    }

    public function adminDownloadPdfLaporanAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $dataAbsen = $dataSiswa->load('absens')->absens()->latest()->get();

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with('error', 'Data absen belum ada!');
        }

        $listKeterangan = $this->absenService->totalKeterangan($dataAbsen);

        $pdf = DomPDF::loadView('absen::layouts.admin.siswa.pdf_user', [
            'listAbsen' => $dataAbsen,
            'totalAbsen' => $listKeterangan['totalAbsen'],
            'name' => $dataSiswa->name,
            'totalHadir' => $listKeterangan['totalHadir'],
            'totalSakit' => $listKeterangan['totalSakit'],
            'totalAcara' => $listKeterangan['totalAcara'],
            'totalMusibah' => $listKeterangan['totalMusibah'],
            'totalTidakHadir' => $listKeterangan['totalTidakHadir'],
        ]);

        return $pdf->download('laporan pdf absen ' . $dataSiswa->name . '.pdf');
    }

    public function adminDownloadExcelLaporanAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $dataAbsen = $dataSiswa->load('absens')->absens()->latest()->get();

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with(['error' => 'Data absen belum ada!']);
        }

        return ExportExcel::download(new ExportAbsen($dataAbsen), 'laporan excel absen ' . $dataSiswa['name'] . '.xlsx');
    }

    public function deleteLaporanAbsenSiswa(DeleteReportAbsenRequest $request)
    {
        $validateData = $request->validated();

        $dataSiswa = Siswa::where('uuid', $validateData['uuid'])->first()->load('absens');
        $dataAbsen = $dataSiswa->absens;

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/siswa')->with('error', 'Data laporan tidak ditemukan!');
        }

        foreach ($dataAbsen as $absen) {
            $absen->delete();
        }

        return redirect('/data-absen/siswa')->with('success', 'Data laporan absen berhasil dihapus!');
    }

    public function downloadZipLaporanAbsenSiswaPdf($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-absen/siswa')->with(['error' => 'Kelas tidak valid!']);
        }

        $dataSiswa = Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Data siswa kelas ini tidak ditemukan!');
        }

        foreach ($dataSiswa as $siswa) {
            $checkExistsAbsen = $siswa->load('absens')->absens->first();

            if ($checkExistsAbsen) {
                $dataAbsen = $siswa->load('absens')->absens()->latest()->get();
                $listKeterangan = $this->absenService->totalKeterangan($dataAbsen);

                $pdf = DomPDF::loadView('absen::layouts.admin.siswa.pdf_user', [
                    'listAbsen' => $dataAbsen,
                    'totalAbsen' => $listKeterangan['totalAbsen'],
                    'name' => $siswa->name,
                    'totalHadir' => $listKeterangan['totalHadir'],
                    'totalSakit' => $listKeterangan['totalSakit'],
                    'totalAcara' => $listKeterangan['totalAcara'],
                    'totalMusibah' => $listKeterangan['totalMusibah'],
                    'totalTidakHadir' => $listKeterangan['totalTidakHadir'],
                ]);

                $pdf->save(public_path('storage/document_laporan_pdf_absen_siswa/' . $siswa->name . '.pdf'));
            }
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_absen_siswa');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Laporan pdf absen tidak ada!');
        }

        $zipFileName = 'laporan_pdf_absen_siswa_kelas_' . $saveClassFromCall . '.zip';
        $createZip = $this->absenService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipLaporanAbsenSiswaExcel($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-absen/siswa')->with(['error' => 'Kelas tidak valid!']);
        }

        // Star EXCEL
        $listSiswa =  Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($listSiswa->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Data siswa kelas ini tidak ditemukan!');
        }

        foreach ($listSiswa as $siswa) {
            $checkExistsAbsen = $siswa->load('absens')->absens->first();

            if ($checkExistsAbsen) {
                $absen = $siswa->load('absens')->absens()->latest()->get();
                $fileName = 'laporan absen ' . $siswa['name'] . '.xlsx';

                ExportExcel::store(new ExportAbsen($absen), $fileName, 'public');

                $sourcePath = storage_path('app/public/' . $fileName);
                $destinationPath = storage_path('app/public/document_laporan_excel_absen_siswa/' . $fileName);

                File::move($sourcePath, $destinationPath);
            }
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_absen_siswa');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_absen_kelas_' . $saveClassFromCall . '.zip';
        $createZip = $this->absenService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function editTanggalAbsenSiswa($saveUuidFromCall, $saveDateFromCaller)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data absen tidak valid!']);
        }

        $timestamp = strtotime($saveDateFromCaller);

        if ($timestamp === false) {
            return redirect('/data-absen/siswa')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $dataUserAuth = Session::get('userData');
        $dataAbsen = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataAbsen) {
            return redirect('/data-absen/siswa')->with('error', 'Data absen tidak ditemukan!');
        }

        return view('absen::layouts.admin.siswa.edit_date', compact('dataUserAuth', 'dataAbsen'));
    }

    public function updateTanggalAbsenSiswa($saveUuidFromCall, UpdateDateAbsenRequest $request)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen/siswa')->with(['error' => 'Data absen tidak valid!']);
        }

        $validateData = $request->validated();

        $dataSiswa = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen/siswa')->with('error', 'Data siswa tidak ditemukan!');
        }

        $dataSiswa->update([
            'keterangan' => $validateData['keterangan'],
        ]);

        return redirect('/data-absen/siswa/' . $dataSiswa->status)->with('success', 'Berhasil update data absen!');
    }

    public function deleteTanggalAbsenSiswa(DeleteDateAbsenSiswaRequest $request)
    {
        $validateData = $request->validated();

        $dataAbsen = Absen::where('uuid', $validateData['uuid'])->first();

        if (!$dataAbsen) {
            return redirect('/data-absen/siswa')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $dataAbsen->delete();

        return redirect('/data-absen/siswa/' . $dataAbsen->status)->with('success', 'Data tanggal absen berhasil dihapus!');
    }
}
