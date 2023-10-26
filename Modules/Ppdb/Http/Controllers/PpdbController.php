<?php

namespace Modules\Ppdb\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Ppdb\Entities\{OpenPpdb, Ppdb};
use Modules\Ppdb\Exports\{ExportPpdb, ExportPpdbZip};
use Modules\Ppdb\Http\Requests\{OpenOrClosePpdbRequest, StorePpdbRequest, UpdatePpdbRequest};
use Modules\Ppdb\Services\PpdbService;
use Modules\Siswa\Entities\Siswa;
use ZipArchive;

class PpdbController extends Controller
{
    protected $userService;

    protected $ppdbService;

    public function __construct(UserService $userService, PpdbService $ppdbService)
    {
        $this->userService = $userService;
        $this->ppdbService = $ppdbService;
    }

    public function register()
    {
        $timeBox = $this->ppdbService->getEditTime();

        return view('ppdb::layouts.register', compact('timeBox'));
    }

    public function store(StorePpdbRequest $request)
    {
        $validateData = $request->validated();

        $checkIfDataAlreadyExists = Ppdb::where('email', $validateData['email'])
            ->orWhere('nisn', $validateData['nisn'])
            ->first();

        if ($checkIfDataAlreadyExists) {
            return redirect('/ppdb')->with(['error' => 'NISN dan Email sudah terdaftar!']);
        }

        $this->ppdbService->saveDataSiswaPpdb($validateData);

        return redirect()->route('ppdb.register')->with(['success' => 'Data ppdb anda berhasil dikirim! Tolong check email dalam 24 jam']);
    }

    public function year()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $allYears = Ppdb::orderBy('tahun_daftar', 'desc')->pluck('tahun_daftar');
        $listYearPpdb = $this->ppdbService->getListYearPpdb($allYears);
        $timeBox = $this->ppdbService->getOpenPpdbTime();
        $openOrClosePpdb = OpenPpdb::first();

        return view('ppdb::layouts.admin.year', compact('dataUserAuth', 'listYearPpdb', 'timeBox', 'openOrClosePpdb'));
    }

    public function openPpdb(OpenOrClosePpdbRequest $request)
    {
        $validateData = $request->validated();

        $this->ppdbService->openPpdb($validateData);

        return redirect()->route('ppdb.year')->with(['success' => 'Berhasil membuka pendaftaran ppdb!']);
    }

    public function closePpdb()
    {
        OpenPpdb::first()->delete();

        return redirect()->route('ppdb.year')->with(['success' => 'Berhasil menutup pendaftaran ppdb!']);
    }

    public function showYear($saveYearFromCall)
    {
        $this->ppdbService->checkValidYear($saveYearFromCall);

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromCall)->latest()->get();
        $totalDataPpdb = $getDataPpdb->count();

        if ($getDataPpdb->isEmpty()) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data ppdb di tahun ini tidak ada!');
        }

        return view('ppdb::layouts.admin.show_year', compact('dataUserAuth', 'getDataPpdb', 'totalDataPpdb', 'saveYearFromCall'));
    }

    public function downloadZipLaporanPpdbPdf($saveYearFromCall)
    {
        $this->ppdbService->checkValidYear($saveYearFromCall);

        $dataPpdb = Ppdb::where('tahun_daftar', $saveYearFromCall)->latest()->get();

        if ($dataPpdb->isEmpty()) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data ppdb di tahun ini tidak ada!');
        }

        foreach ($dataPpdb as $ppdb) {
            $pdf = DomPDF::loadView('ppdb::layouts.admin.pdf', [
                'ppdb' => $ppdb,
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_ppdb/' . $ppdb->name . '.pdf'));
        }

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_ppdb');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Laporan pdf ppdb tidak ada!');
        }

        $zipFileName = 'laporan_pdf_ppdb_tahun_' . $saveYearFromCall . '.zip';
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

    public function downloadLaporanPpdbPdf($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data tidak valid!');
        }

        $biodataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        $pdf = DomPDF::loadView('ppdb::layouts.admin.pdf', [
            'ppdb' => $biodataPpdb
        ]);

        return $pdf->download('laporan pdf ppdb ' . $biodataPpdb->name . '.pdf');
    }

    public function downloadZipLaporanPpdbExcel($saveYearFromCall)
    {
        // Star EXCEL
        $listPpdb =  Ppdb::where('tahun_daftar', $saveYearFromCall)->latest()->get();

        if ($listPpdb->isEmpty()) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Data ppdb tidak ditemukan!');
        }

        foreach ($listPpdb as $ppdb) {
            $fileName = 'biodata ' . $ppdb['name'] . 'ppdb ' . $ppdb['tahun_daftar'] . '.xlsx';

            ExportExcel::store(new ExportPpdbZip($ppdb->uuid), $fileName, 'public');

            $sourceDirectory = public_path('storage/');
            $files = scandir($sourceDirectory);

            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) == 'xlsx' && strpos($file, 'biodata') !== false) {
                    $sourcePath = public_path('storage/' . $file);
                    $destinationPath = public_path('storage/document_laporan_excel_ppdb/' . $file);
                    File::move($sourcePath, $destinationPath);
                }
            }
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_ppdb');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_ppdb_tahun_' . $saveYearFromCall . '.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with(['error' => 'Gagal membuat arsip ZIP']);
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

    public function downloadLaporanPpdbExcel($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data tidak valid!');
        }

        $biodataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataPpdb) {
            return abort(404);
        }

        return ExportExcel::download(new ExportPpdb($saveUuidFromCall), 'laporan excel ppdb ' . $biodataPpdb->name . '.xlsx');
    }

    public function show($saveUuidFromRoute)
    {
        $this->ppdbService->checkUuidOrNot($saveUuidFromRoute);

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataUserPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataUserPpdb) {
            return abort(404);
        }

        $checkSiswaOrNot = Siswa::where('nisn', $getDataUserPpdb->nisn)->exists();

        return view('ppdb::layouts.admin.show', compact('dataUserAuth', 'getDataUserPpdb', 'checkSiswaOrNot'));
    }

    public function accept($saveUuidFromRoute)
    {
        $this->ppdbService->checkUuidOrNot($saveUuidFromRoute);

        $getDataUserPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataUserPpdb) {
            return abort(404);
        }

        $this->ppdbService->acceptPpdb($saveUuidFromRoute);

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $getDataUserPpdb->tahun_daftar])->with(['success' => 'Peserta ppdb berhasil di terima!']);
    }

    public function edit($saveUuidFromRoute)
    {
        $this->ppdbService->checkUuidOrNot($saveUuidFromRoute);

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataUserPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataUserPpdb) {
            return abort(404);
        }

        $timeBox = $this->ppdbService->getEditTime();

        return view('ppdb::layouts.admin.edit', compact('dataUserAuth', 'getDataUserPpdb', 'timeBox'));
    }

    public function update(UpdatePpdbRequest $request, $saveUuidFromRoute)
    {
        $this->ppdbService->checkUuidOrNot($saveUuidFromRoute);

        $validateData = $request->validated();
        $this->ppdbService->editPpdb($validateData, $saveUuidFromRoute);

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $validateData['tahun_daftar']])->with(['success' => 'Data ppdb berhasil di edit!']);
    }

    public function delete($saveUuidFromRoute)
    {
        $this->ppdbService->checkUuidOrNot($saveUuidFromRoute);

        $getDataUserPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();

        if (!$getDataUserPpdb) {
            return abort(404);
        }

        if ($getDataUserPpdb->bukti_pendaftaran !== 'assets/dashboard/img/surat-ppdb.png') {
            File::delete($getDataUserPpdb->bukti_pendaftaran);
        }

        $getDataUserPpdb->delete();

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $getDataUserPpdb->tahun_daftar])->with('success', 'Data ppdb sudah berhasil dihapus!');
    }
}
