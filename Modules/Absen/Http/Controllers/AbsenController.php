<?php

namespace Modules\Absen\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\DeleteReportAbsenRequest;
use Modules\Absen\Http\Requests\DeleteTanggalAbsenRequest;
use Modules\Absen\Http\Requests\DownloadPdfAbsenClassRequest;
use Modules\Absen\Http\Requests\DownloadPdfAbsenRequest;
use Modules\Absen\Http\Requests\StoreAbsenRequest;
use Modules\Absen\Http\Requests\UpdateDateAbsenSiswa;
use Modules\Absen\Services\AbsenService;
use Modules\Siswa\Services\SiswaService;
use ZipArchive;

class AbsenController extends Controller
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

    public function absen()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        if (!$dataUserAuth[0]->siswa) {
            return redirect('/dashboard')->with('error', 'Data absen belum ada!');
        }

        $checkAbsenOrNot = Absen::where('nisn', $dataUserAuth[0]->siswa->nisn)->latest()->first();
        $today = now()->format('Y-m-d');

        if ($checkAbsenOrNot && $dataUserAuth[0]->siswa->nisn == $checkAbsenOrNot->nisn && $checkAbsenOrNot->created_at->format('Y-m-d') == $today) {
            return view('absen::pages.absen.page', compact('dataUserAuth', 'checkAbsenOrNot'))->with('success', 'Anda sudah melakukan absen!');
        }

        $checkAbsenOrNot = null;

        return view('absen::pages.absen.page', compact('dataUserAuth', 'checkAbsenOrNot'));
    }

    public function store(StoreAbsenRequest $request)
    {
        $validateData = $request->validated();

        $checkAbsenOrNot = Absen::where('nisn', $validateData['nisn'])->latest()->first();
        $today = now()->format('Y-m-d');

        if ($checkAbsenOrNot && $checkAbsenOrNot->created_at->format('Y-m-d') == $today) {
            return redirect('/dashboard')->with('error', 'Anda sudah melakukan absen!');
        }

        $this->absenService->create($validateData);

        return redirect('/dashboard')->with('success', 'Anda berhasil melakukan absen!');
    }

    public function laporan()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        if (!$dataUserAuth[0]->siswa) {
            return redirect('/dashboard')->with('error', 'Data absen belum ada!');
        }

        $getDataAbsen = Absen::where('nisn', $dataUserAuth[0]->siswa->nisn)->latest()->get();

        if ($getDataAbsen->isEmpty()) {
            return redirect('/dashboard')->with('error', 'Data absen belum ada!');
        }

        return view('absen::pages.absen.laporan', compact('dataUserAuth', 'getDataAbsen'));
    }

    public function downloadPdfLaporanAbsen(DownloadPdfAbsenRequest $request)
    {
        $validateData = $request->validated();

        $getDataAbsen = Absen::where('nisn', $validateData['nisn'])->latest()->get();
        $listKehadiran = $this->absenService->getTotalKehadiran($getDataAbsen);

        if ($getDataAbsen->isEmpty()) {
            return abort(404);
        }

        $pdf = DomPDF::loadView('absen::layouts.absen.pdf_user', [
            'dataAbsen' => $getDataAbsen,
            'totalAbsen' => $listKehadiran['totalAbsen'],
            'name' => Auth::user()->name,
            'totalHadir' => $listKehadiran['totalHadir'],
            'totalSakit' => $listKehadiran['totalSakit'],
            'totalAcara' => $listKehadiran['totalAcara'],
            'totalMusibah' => $listKehadiran['totalMusibah'],
            'totalTidakHadir' => $listKehadiran['totalTidakHadir'],
        ]);

        return $pdf->download('laporan pdf absen ' . Auth::user()->name . '.pdf');
    }

    public function getListClass()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        return view('absen::pages.absen.siswa.list_class', compact('dataUserAuth'));
    }

    public function showClass($saveClassFromObjectCall)
    {
        if (!is_numeric($saveClassFromObjectCall) || !in_array($saveClassFromObjectCall, ['10', '11', '12'])) {
            return redirect('/dashboard')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();

        $getListAbsen = Absen::where('status', $saveClassFromObjectCall)
            ->latest()
            ->get();

        if ($getListAbsen->isEmpty()) {
            return redirect()->route('data.absen')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $listNisnAbsen = $getListAbsen->unique('nisn')->pluck('nisn')->toArray();
        $totalAbsen = count($listNisnAbsen);
        $dataAbsen = [];
        foreach ($listNisnAbsen as $nisn) {
            $getDataAbsen = Absen::where('nisn', $nisn)->first();
            $dataAbsen[] = $getDataAbsen;
        }

        return view('absen::pages.absen.siswa.show_class', compact('dataUserAuth', 'saveClassFromObjectCall', 'dataAbsen', 'totalAbsen'));
    }

    public function showDataSiswa($saveNisnFromObjectCall)
    {
        if (!is_string($saveNisnFromObjectCall) || strlen($saveNisnFromObjectCall) > 10) {
            return redirect('/absen-data')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataAbsen = Absen::where('nisn', $saveNisnFromObjectCall)->latest()->get();

        if ($getDataAbsen->isEmpty()) {
            return redirect('/absen-data')->with(['error' => 'Data absen tidak ditemukan!']);
        }

        $listKehadiran = $this->absenService->getTotalKehadiran($getDataAbsen);
        $listTanggalAbsen = $getDataAbsen->pluck('created_at')->map(function ($date) {
            return $date->format('Y-m-d H:i:s');
        });

        return view('absen::pages.absen.siswa.show_data', compact('dataUserAuth', 'getDataAbsen', 'listKehadiran', 'listTanggalAbsen'));
    }

    public function deleteLaporanAbsenSiswa(DeleteReportAbsenRequest $request)
    {
        $validateData = $request->validated();

        $getDataAbsen = Absen::where('nisn', $validateData['nisn'])->latest()->get();

        if ($getDataAbsen->isEmpty()) {
            return redirect('/absen-data')->with('error', 'Data laporan tidak ditemukan!');
        }

        foreach ($getDataAbsen as $absen) {
            $absen->delete();
        }

        return redirect('/absen-data')->with('success', 'Data laporan absen berhasil dihapus!');
    }

    public function downloadZipLaporanAbsenClass(DownloadPdfAbsenClassRequest $request)
    {
        $validateData = $request->validated();

        if (!in_array($validateData['kelas'], ['10', '11', '12'])) {
            return redirect('/absen-data')->with('error', 'Kelas tidak di temukan!');
        }

        // Star PDF
        $getListNisn = Absen::where('status', $validateData['kelas'])->distinct()->pluck('nisn');
        if ($getListNisn->isEmpty()) {
            return redirect('/absen-data')->with('error', 'Data absen tidak ada!');
        }

        $listLaporanAbsen = $this->absenService->getListLaporanAbsenSiswa($getListNisn);
        if (empty($listLaporanAbsen)) {
            return redirect('/absen-data')->with('error', 'Data absen tidak ada!');
        }

        $createPdfLaporanAbsenSiswa = $this->absenService->createPdfLaporanAbsenSiswa($listLaporanAbsen, $validateData['kelas']);
        if ($createPdfLaporanAbsenSiswa != 'success') {
            return redirect('/absen-data/' . $validateData['kelas'])->with('error', 'Gagal membuat arsip PDF!');
        }
        // End PDF

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_absen_kelas_' . $validateData['kelas']);
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/absen-data/' . $validateData['kelas'])->with('error', 'Laporan absen tidak ada!');
        }

        $zipFileName = 'laporan_absen_kelas_' . $validateData['kelas'] . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/dashboard')->with(['error' => 'Gagal membuat arsip ZIP']);
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

    public function editTanggalAbsenSiswa($saveUuidFromCaller, $saveDateFromCaller)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return redirect('/absen-data')->with(['error' => 'Data tanggal absen tidak ditemukan!']);
        }

        $timestamp = strtotime($saveDateFromCaller);

        if ($timestamp === false) {
            return redirect('/absen-data')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataAbsen = Absen::where('uuid', $saveUuidFromCaller)->where('created_at', $saveDateFromCaller)->first();

        if (!$getDataAbsen) {
            return redirect('/absen-data')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        return view('absen::pages.absen.siswa.edit_date', compact('dataUserAuth', 'getDataAbsen', 'saveDateFromCaller'));
    }

    public function updateTanggalAbsenSiswa($saveUuidFromCaller, UpdateDateAbsenSiswa $request)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return redirect('/absen-data')->with(['error' => 'Data tanggal absen tidak ditemukan!']);
        }

        $validateData = $request->validated();

        $getDataAbsen = Absen::where('uuid', $saveUuidFromCaller)
            ->latest()
            ->first();

        if (!$getDataAbsen) {
            return redirect('/absen-data')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $getDataAbsen->update([
            'kehadiran' => $validateData['kehadiran'],
        ]);

        return redirect('/absen-data')->with('success', 'Berhasil update data absen!');
    }

    public function deleteTanggalAbsenSiswa(DeleteTanggalAbsenRequest $request)
    {
        $validateData = $request->validated();

        $getDataAbsen = Absen::where('nisn', $validateData['nisn'])
            ->where('created_at', $validateData['tanggal'])
            ->latest()
            ->first();

        if (!$getDataAbsen) {
            return redirect('/absen-data')->with('error', 'Data tanggal absen tidak ditemukan!');
        }

        $getDataAbsen->delete();

        return redirect('/absen-data')->with('success', 'Data tanggal absen berhasil dihapus!');
    }
}
