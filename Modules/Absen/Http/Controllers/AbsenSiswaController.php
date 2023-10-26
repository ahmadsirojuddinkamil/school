<?php

namespace Modules\Absen\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\{DeleteDateAbsenSiswaRequest, DeleteReportAbsenRequest, UpdateDateAbsenRequest};
use Modules\Absen\Services\AbsenService;
use Modules\Siswa\Entities\Siswa;
use Modules\Siswa\Services\SiswaService;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Absen\Exports\ExportAbsen;
use ZipArchive;

class AbsenSiswaController extends Controller
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

    public function getListClass()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $listSiswaInClass = $this->siswaService->getListSiswaClass();

        return view('absen::layouts.admin.siswa.list_class', compact('dataUserAuth', 'listSiswaInClass'));
    }

    public function showClass($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/dashboard')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();

        $listSiswa = Siswa::where('kelas', $saveClassFromCall)
            ->latest()
            ->get();

        if ($listSiswa->isEmpty()) {
            return redirect()->route('data.absen')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $totalSiswa = count($listSiswa);

        return view('absen::layouts.admin.siswa.show_class', compact('dataUserAuth', 'saveClassFromCall', 'listSiswa', 'totalSiswa'));
    }

    public function showDataAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $listAbsen = $dataSiswa->absens()->latest()->get();

        $listKehadiran = $this->absenService->getTotalKeterangan($listAbsen);

        return view('absen::layouts.admin.siswa.show_report', compact('dataUserAuth', 'dataSiswa', 'listKehadiran', 'listAbsen'));
    }

    public function adminDownloadPdfLaporanAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();
        $dataAbsen = $dataSiswa->absens()->latest()->get();
        $listKeterangan = $this->absenService->getTotalKeterangan($dataAbsen);

        if (empty($listKeterangan)) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with('error', 'Data absen belum ada!');
        }

        $pdf = DomPDF::loadView('absen::layouts.admin.siswa.pdf_user', [
            'listAbsen' => $dataAbsen,
            'totalAbsen' => $listKeterangan['totalAbsen'],
            'name' => $dataSiswa->nama_lengkap,
            'totalHadir' => $listKeterangan['totalHadir'],
            'totalSakit' => $listKeterangan['totalSakit'],
            'totalAcara' => $listKeterangan['totalAcara'],
            'totalMusibah' => $listKeterangan['totalMusibah'],
            'totalTidakHadir' => $listKeterangan['totalTidakHadir'],
        ]);

        return $pdf->download('laporan pdf absen ' . $dataSiswa->nama_lengkap . '.pdf');
    }

    public function adminDownloadExcelLaporanAbsen($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen')->with(['error' => 'Data absen tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $dataAbsen = $dataSiswa->absens()->latest()->get();

        if ($dataAbsen->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveUuidFromCall . '/show')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        return ExportExcel::download(new ExportAbsen($dataAbsen), 'laporan absen ' . $dataSiswa['name'] . '.xlsx');
    }

    public function deleteLaporanAbsenSiswa(DeleteReportAbsenRequest $request)
    {
        $validateData = $request->validated();
        $data_absen = json_decode($validateData['data_absen']);

        if (empty($data_absen)) {
            return redirect('/data-absen')->with('error', 'Data laporan tidak ditemukan!');
        }

        foreach ($data_absen as $absen) {
            $absenModel = Absen::where('uuid', $absen->uuid)->first();
            $absenModel->delete();
        }

        return redirect('/data-absen')->with('success', 'Data laporan absen berhasil dihapus!');
    }

    public function downloadZipLaporanAbsenSiswaPdf($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-absen')->with(['error' => 'Kelas tidak valid!']);
        }

        $dataSiswa = Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Data absen tidak ditemukan!');
        }

        foreach ($dataSiswa as $siswa) {
            $pdf = DomPDF::loadView('absen::layouts.admin.siswa.pdf_siswa', [
                'name' => $siswa->name,
                'dataAbsen' => $siswa->absens()->latest()->get(),
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_absen/' . $siswa->name . '.pdf'));
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_absen');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Laporan pdf absen tidak ada!');
        }

        $zipFileName = 'laporan_absen_siswa_kelas_' . $saveClassFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with(['error' => 'Gagal membuat arsip ZIP']);
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

    public function downloadZipLaporanAbsenSiswaExcel($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-absen')->with(['error' => 'Kelas tidak di temukan!']);
        }

        // Star EXCEL
        $listSiswa =  Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($listSiswa->isEmpty()) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Data siswa kelas ini tidak ditemukan!');
        }

        foreach ($listSiswa as $siswa) {
            $absen = $siswa->absens()->latest()->get();
            $fileName = 'laporan absen ' . $siswa['name'] . ' kelas ' . $saveClassFromCall . '.xlsx';

            ExportExcel::store(new ExportAbsen($absen), $fileName, 'public');

            $sourcePath = storage_path('app/public/' . $fileName);
            $destinationPath = storage_path('app/public/document_laporan_excel_absen/' . $fileName);

            File::move($sourcePath, $destinationPath);
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_absen');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_absen_kelas_' . $saveClassFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-absen/siswa/' . $saveClassFromCall)->with('error', 'Gagal membuat arsip ZIP');
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

    public function editTanggalAbsenSiswa($saveUuidFromCall, $saveDateFromCaller)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen')->with(['error' => 'Data tanggal absen tidak ditemukan!']);
        }

        $timestamp = strtotime($saveDateFromCaller);

        if ($timestamp === false) {
            return redirect('/data-absen')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataAbsen = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataAbsen) {
            return redirect('/data-absen')->with('error', 'Data absen tidak ditemukan!');
        }

        return view('absen::layouts.admin.siswa.edit_date', compact('dataUserAuth', 'dataAbsen'));
    }

    public function updateTanggalAbsenSiswa($saveUuidFromCall, UpdateDateAbsenRequest $request)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-absen')->with(['error' => 'Data tanggal absen tidak ditemukan!']);
        }

        $validateData = $request->validated();

        $dataSiswa = Absen::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-absen')->with('error', 'Data siswa tidak ditemukan!');
        }

        $dataSiswa->update([
            'keterangan' => $validateData['keterangan'],
        ]);

        return redirect('/data-absen/siswa/' . $dataSiswa->status)->with('success', 'Berhasil update data absen!');
    }

    public function deleteTanggalAbsenSiswa(DeleteDateAbsenSiswaRequest $request)
    {
        $validateData = $request->validated();

        $getDataAbsen = Absen::where('uuid', $validateData['uuid'])->first();

        if (!$getDataAbsen) {
            return redirect('/data-absen')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $getDataAbsen->delete();

        return redirect('/data-absen/siswa/' . $validateData['kelas'])->with('success', 'Data tanggal absen berhasil dihapus!');
    }
}
