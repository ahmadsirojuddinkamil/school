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

class SiswaController extends Controller
{
    protected $userService;
    protected $siswaService;

    public function __construct(UserService $userService, SiswaService $siswaService)
    {
        $this->userService = $userService;
        $this->siswaService = $siswaService;
    }

    public function status()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $statusSiswa = $this->siswaService->statusSiswaActiveOrNot();

        return view('siswa::layouts.admin.status', compact('dataUserAuth', 'statusSiswa'));
    }

    public function listClass()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $listSiswaInClass = $this->siswaService->listSiswaInClass();

        if (!$listSiswaInClass) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.list_class', compact('dataUserAuth', 'listSiswaInClass'));
    }

    public function showClass($saveClassFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data tidak valid!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $listSiswa = Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($listSiswa->isEmpty()) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.show_class', compact('dataUserAuth', 'saveClassFromCall', 'listSiswa'));
    }

    public function siswaActive($saveClassFromCall, $saveUuidFromCall)
    {
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data tidak valid!']);
        }

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with(['error' => 'Data tidak valid!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.show_active', compact('dataUserAuth', 'dataSiswa'));
    }

    // START ACTIVE
    public function downloadZipSiswaActivePdf($saveClassFromCall)
    {
        // Star PDF
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Data siswa tidak ditemukan!');
        }

        foreach ($dataSiswa as $siswa) {
            $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_active', [
                'siswa' => $siswa,
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_siswa_active/' . $siswa->name . '.pdf'));
        }
        // End PDF

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_siswa_active');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Laporan pdf siswa tidak ada!');
        }

        $zipFileName = 'laporan_pdf_siswa_kelas_' . $saveClassFromCall . '.zip';
        $createZip = $this->siswaService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipSiswaActiveExcel($saveClassFromCall)
    {
        // Star EXCEL
        if (!is_numeric($saveClassFromCall) || !in_array($saveClassFromCall, ['10', '11', '12'])) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak valid!']);
        }

        $listSiswa =  Siswa::where('kelas', $saveClassFromCall)->latest()->get();

        if ($listSiswa->isEmpty()) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Data siswa tidak ditemukan!');
        }

        foreach ($listSiswa as $siswa) {
            $fileName = 'biodata ' . $siswa['name'] . 'kelas ' . $saveClassFromCall . '.xlsx';

            ExportExcel::store(new ExportSiswaActive($siswa->uuid), $fileName, 'public');

            $sourcePath = storage_path('app/public/' . $fileName);
            $destinationPath = storage_path('app/public/document_laporan_excel_siswa_active/' . $fileName);

            File::move($sourcePath, $destinationPath);
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_siswa_active');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_siswa_kelas_' . $saveClassFromCall . '.zip';
        $createZip = $this->siswaService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-siswa/status/aktif/kelas/' . $saveClassFromCall)->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadPdfSiswaActive($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_active', [
            'siswa' => $dataSiswa,
        ]);

        return $pdf->download('laporan pdf siswa ' . $dataSiswa['name'] . '.pdf');
    }

    public function downloadExcelSiswaActive($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/status/aktif/kelas')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return ExportExcel::download(new ExportSiswaActive($saveUuidFromCall), 'laporan siswa ' . $dataSiswa['name'] . '.xlsx');
    }
    // END ACTIVE

    // START GRADUATED
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
        $createZip = $this->siswaService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipSiswaGraduatedExcel($saveYearFromCall)
    {
        // Star EXCEL
        if (!preg_match('/^\d{4}$/', $saveYearFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Data siswa tidak valid!');
        }

        $dataSiswa = Siswa::where('tahun_keluar', $saveYearFromCall)->latest()->get();

        if ($dataSiswa->isEmpty()) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Data siswa lulus tidak ditemukan!');
        }

        foreach ($dataSiswa as $siswa) {
            $fileName = 'biodata ' . $siswa['name'] . ' tahun ' . $saveYearFromCall . '.xlsx';

            ExportExcel::store(new ExportSiswaGraduated($siswa->uuid), $fileName, 'public');

            $sourcePath = storage_path('app/public/' . $fileName);
            $destinationPath = storage_path('app/public/document_laporan_excel_siswa_graduated/' . $fileName);

            File::move($sourcePath, $destinationPath);
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_siswa_graduated');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_siswa_tahun_' . $saveYearFromCall . '.zip';
        $createZip = $this->siswaService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-siswa/status/sudah-lulus')->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadPdfSiswaGraduated($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/' . $saveUuidFromCall . '/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $pdf = DomPDF::loadView('siswa::layouts.admin.pdf_graduated', [
            'siswa' => $dataSiswa,
        ]);

        return $pdf->download('laporan pdf siswa ' . $dataSiswa['name'] . '.pdf');
    }

    public function downloadExcelSiswaGraduated($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/' . $saveUuidFromCall . '/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return ExportExcel::download(new ExportSiswaGraduated($dataSiswa->uuid), 'laporan excel siswa ' . $dataSiswa['name'] . '.xlsx');
    }
    // END GRADUATED

    public function siswaGraduated()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $dataSiswa = Siswa::whereNotNull('tahun_keluar')->latest()->get();

        $listYearGraduated = Siswa::whereNotNull('tahun_keluar')
            ->distinct()
            ->orderBy('tahun_keluar', 'desc')
            ->pluck('tahun_keluar')
            ->toArray();

        return view('siswa::layouts.admin.graduated', compact('dataUserAuth', 'dataSiswa', 'listYearGraduated'));
    }

    public function showSiswaGraduated($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::layouts.admin.show_graduated', compact('dataUserAuth', 'dataSiswa'));
    }

    public function edit($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        $timeBox = $this->siswaService->getEditTime();

        return view('siswa::layouts.admin.edit', compact('dataUserAuth', 'dataSiswa', 'timeBox'));
    }

    public function update(UpdateSiswaRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $this->siswaService->updateDataSiswa($validateData, $saveUuidFromCall);

        $dataUserAuth = $this->userService->getProfileUser();
        if ($dataUserAuth[1] == 'siswa') {
            return redirect('/data-siswa/status/aktif/kelas/' . $dataUserAuth[0]->siswa->kelas . '/' . $dataUserAuth[0]->siswa->uuid)->with(['success' => 'Data siswa berhasil di edit!']);
        }

        return redirect('/data-siswa/status/sudah-lulus')->with(['success' => 'Data siswa berhasil di edit!']);
    }

    public function delete($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak valid!']);
        }

        $dataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$dataSiswa) {
            return redirect('/data-siswa/status/sudah-lulus')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        if ($dataSiswa->foto !== 'assets/dashboard/img/foto-siswa.png') {
            File::delete($dataSiswa->foto);
        }

        $dataSiswa->delete();

        return redirect('/data-siswa/status/sudah-lulus')->with(['success' => 'Data siswa berhasil di hapus!']);
    }
}
