<?php

namespace Modules\Siswa\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Siswa\Entities\Siswa;
use Modules\Siswa\Exports\{ExportSiswaActive, ExportSiswaGraduated};
use Modules\Siswa\Http\Requests\{UpdateSiswaRequest};
use Modules\Siswa\Services\SiswaService;
use ZipArchive;

class SiswaController extends Controller
{
    protected $userService;
    protected $siswaService;

    public function __construct(UserService $userService, SiswaService $siswaService)
    {
        $this->userService = $userService;
        $this->siswaService = $siswaService;
    }

    public function getStatus()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $getStatusSiswa = $this->siswaService->getStatusSiswaActiveOrNot();

        return view('siswa::layouts.admin.status', compact('dataUserAuth', 'getStatusSiswa'));
    }

    public function getListClassSiswa()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $getListClassSiswa = $this->siswaService->getListSiswaClass();

        if (!$getListClassSiswa) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.list_class', compact('dataUserAuth', 'getListClassSiswa'));
    }

    public function showSiswaClass($saveClassFromRoute)
    {
        if (!is_numeric($saveClassFromRoute)) {
            return redirect()->route('siswa.status')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $getListSiswa = Siswa::where('kelas', $saveClassFromRoute)->latest()->get();

        if ($getListSiswa->isEmpty()) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.show_class', compact('dataUserAuth', 'saveClassFromRoute', 'getListSiswa'));
    }

    public function showSiswaActive($saveClassFromRoute, $saveUuidFromRoute)
    {
        if (!is_numeric($saveClassFromRoute) || !in_array($saveClassFromRoute, ['10', '11', '12'])) {
            return redirect()->route('siswa.status')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $this->siswaService->checkGraduatedUuidOrNot($saveUuidFromRoute);

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataSiswa = Siswa::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataSiswa) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.show_active', compact('dataUserAuth', 'getDataSiswa'));
    }

    public function downloadPdfSiswaActive($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_active', [
            'siswa' => $dataSiswa,
        ]);

        return $pdf->download('laporan pdf siswa ' . $dataSiswa['name'] . '.pdf');
    }

    public function downloadZipSiswaActivePdf($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect()->route('siswa.status')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $dataSiswa = Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Data siswa di kelas ini tidak ada!');
        }

        foreach ($dataSiswa as $siswa) {
            $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_active', [
                'siswa' => $siswa,
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_siswa/' . $siswa->name . '.pdf'));
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_siswa');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Laporan pdf siswa tidak ada!');
        }

        $zipFileName = 'laporan_pdf_siswa_kelas_' . $saveClassFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Gagal membuat arsip ZIP']);
        }

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
        }

        $zip->close();
        File::deleteDirectory($folderPath);
        File::makeDirectory($folderPath, 0755, true, true);

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipSiswaActiveExcel($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('data-siswa/status/aktif/kelas')->with(['error' => 'Kelas tidak di temukan!']);
        }

        // Star EXCEL
        $listSiswa =  Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($listSiswa->isEmpty()) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Data siswa kelas ini tidak ditemukan!');
        }

        foreach ($listSiswa as $siswa) {
            $fileName = 'biodata ' . $siswa['name'] . 'kelas ' . $saveClassFromCall . '.xlsx';

            ExportExcel::store(new ExportSiswaActive($siswa->uuid), $fileName, 'public');

            $sourceDirectory = public_path('storage/');
            $files = scandir($sourceDirectory);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'xlsx' && strpos($file, 'biodata') !== false) {
                    $sourcePath = public_path('storage/' . $file);
                    $destinationPath = public_path('storage/document_laporan_excel_siswa/' . $file);
                    File::move($sourcePath, $destinationPath);
                }
            }
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_siswa');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_siswa_kelas_' . $saveClassFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with(['error' => 'Gagal membuat arsip ZIP']);
        }

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
        }

        $zip->close();
        File::deleteDirectory($folderPath);
        File::makeDirectory($folderPath, 0755, true, true);

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadExcelSiswaActive($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return ExportExcel::download(new ExportSiswaActive($saveUuidFromCall), 'laporan siswa ' . $dataSiswa['name'] . '.xlsx');
    }

    public function downloadPdfSiswaGraduated($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('data-siswa/' . $saveUuidFromCall . '/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_graduated', [
            'siswa' => $dataSiswa,
        ]);

        return $pdf->download('laporan pdf siswa ' . $dataSiswa['name'] . '.pdf');
    }

    public function downloadZipSiswaGraduatedPdf($saveYearFromCall)
    {
        if (!preg_match('/^\d{4}$/', $saveYearFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Data siswa tidak valid!');
        }

        $dataSiswa = Siswa::where('tahun_keluar', $saveYearFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Data siswa lulus tidak ditemukan!');
        }

        foreach ($dataSiswa as $siswa) {
            $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_graduated', [
                'siswa' => $siswa,
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_siswa_graduated/' . $siswa->name . '.pdf'));
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_siswa_graduated');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Laporan pdf siswa tidak ada!');
        }

        $zipFileName = 'laporan_pdf_siswa_lulus_tahun_' . $saveYearFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Gagal membuat arsip ZIP']);
        }

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
        }

        $zip->close();
        File::deleteDirectory($folderPath);
        File::makeDirectory($folderPath, 0755, true, true);

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipSiswaGraduatedExcel($saveYearFromCall)
    {
        if (!preg_match('/^\d{4}$/', $saveYearFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Data siswa tidak valid!');
        }

        // Star EXCEL
        $dataSiswa = Siswa::where('tahun_keluar', $saveYearFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Data siswa lulus tidak ditemukan!');
        }

        foreach ($dataSiswa as $siswa) {
            $fileName = 'biodata ' . $siswa['name'] . ' tahun ' . $saveYearFromCall . '.xlsx';

            ExportExcel::store(new ExportSiswaGraduated($siswa->uuid), $fileName, 'public');

            $sourceDirectory = public_path('storage/');
            $files = scandir($sourceDirectory);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'xlsx' && strpos($file, 'biodata') !== false) {
                    $sourcePath = public_path('storage/' . $file);
                    $destinationPath = public_path('storage/document_laporan_excel_siswa_graduated/' . $file);
                    File::move($sourcePath, $destinationPath);
                }
            }
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_siswa_graduated');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_siswa_tahun_' . $saveYearFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Gagal membuat arsip ZIP!');
        }

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
        }

        $zip->close();
        File::deleteDirectory($folderPath);
        File::makeDirectory($folderPath, 0755, true, true);

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadExcelSiswaGraduated($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('data-siswa/' . $saveUuidFromCall . '/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return ExportExcel::download(new ExportSiswaGraduated($dataSiswa->uuid), 'laporan excel siswa ' . $dataSiswa['name'] . '.xlsx');
    }

    public function getListSiswaGraduated()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        $getSiswaGraduated = Siswa::whereNotNull('tahun_keluar')->latest()->get();

        if ($getSiswaGraduated->isEmpty()) {
            return redirect('/data-siswa/status')->with(['error' => 'Data siswa lulus tidak ditemukan!']);
        }

        $ListYearGraduated = Siswa::whereNotNull('tahun_keluar')
            ->distinct()
            ->orderBy('tahun_keluar', 'desc')
            ->pluck('tahun_keluar')
            ->toArray();

        return view('siswa::layouts.admin.graduated', compact('dataUserAuth', 'getSiswaGraduated', 'ListYearGraduated'));
    }

    public function showSiswaGraduated($saveUuidFromRoute)
    {
        $this->siswaService->checkGraduatedUuidOrNot($saveUuidFromRoute);
        $dataUserAuth = $this->userService->getProfileUser();

        $getDataSiswa = Siswa::where('uuid', $saveUuidFromRoute)->first();
        if (!$getDataSiswa) {
            return redirect()->route('siswa.graduated')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.show_graduated', compact('dataUserAuth', 'getDataSiswa'));
    }

    public function edit($saveUuidFromRoute)
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $this->siswaService->checkEditUuidOrNot($saveUuidFromRoute);

        $getDataSiswa = Siswa::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataSiswa) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $timeBox = $this->siswaService->getEditTime();

        return view('siswa::layouts.admin.edit', compact('dataUserAuth', 'getDataSiswa', 'timeBox'));
    }

    public function update(UpdateSiswaRequest $request, $saveUuidFromRoute)
    {
        $validateData = $request->validated();
        $this->siswaService->checkUpdateUuidOrNot($saveUuidFromRoute);
        $this->siswaService->updateDataSiswa($validateData, $saveUuidFromRoute);

        $dataUserAuth = $this->userService->getProfileUser();
        if ($dataUserAuth[1] == 'siswa') {
            return redirect('/data-siswa/status/aktif/kelas/' . $dataUserAuth[0]->siswa->kelas . '/' . $dataUserAuth[0]->siswa->uuid)->with(['success' => 'Data siswa berhasil di edit!']);
        }

        return redirect('/data-siswa/status')->with(['success' => 'Data siswa berhasil di edit!']);
    }

    public function delete($saveUuidFromRoute)
    {
        $this->siswaService->checkDeleteUuidOrNot($saveUuidFromRoute);

        $getDataSiswa = Siswa::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataSiswa) {
            return redirect('/data-siswa/status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        if ($getDataSiswa->foto !== 'assets/dashboard/img/foto-siswa.png') {
            File::delete($getDataSiswa->foto);
        }

        $getDataSiswa->delete();

        return redirect()->route('siswa.status')->with(['success' => 'Data siswa berhasil di hapus!']);
    }
}
