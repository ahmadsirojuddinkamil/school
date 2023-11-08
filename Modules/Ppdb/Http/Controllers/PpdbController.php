<?php

namespace Modules\Ppdb\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Ppdb\Entities\{OpenPpdb, Ppdb};
use Modules\Ppdb\Exports\{ExportPpdb};
use Modules\Ppdb\Http\Requests\{OpenOrClosePpdbRequest, StorePpdbRequest, UpdatePpdbRequest};
use Modules\Ppdb\Services\PpdbService;
use Modules\Siswa\Entities\Siswa;

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

        $this->ppdbService->createPpdb($validateData);

        return redirect('/ppdb')->with(['success' => 'Data ppdb anda berhasil dikirim! Tolong check email dalam 24 jam']);
    }

    public function year()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $allYears = Ppdb::orderBy('tahun_daftar', 'desc')->pluck('tahun_daftar');
        $listYearPpdb = $this->ppdbService->listYearPpdb($allYears);
        $timeBox = $this->ppdbService->openPpdbTime();
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
        if (!preg_match('/^\d{4}$/', $saveYearFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataPpdb = Ppdb::where('tahun_daftar', $saveYearFromCall)->latest()->get();
        $totalPpdb = $dataPpdb->count();

        if ($dataPpdb->isEmpty()) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data ppdb di tahun ini tidak ada!');
        }

        return view('ppdb::layouts.admin.show_year', compact('dataUserAuth', 'dataPpdb', 'totalPpdb', 'saveYearFromCall'));
    }

    public function downloadZipLaporanPpdbPdf($saveYearFromCall)
    {
        // Star PDF
        if (!preg_match('/^\d{4}$/', $saveYearFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataPpdb = Ppdb::where('tahun_daftar', $saveYearFromCall)->latest()->get();

        if ($dataPpdb->isEmpty()) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Data ppdb di tahun ini tidak ada!');
        }

        foreach ($dataPpdb as $ppdb) {
            $pdf = DomPDF::loadView('ppdb::layouts.admin.pdf', [
                'ppdb' => $ppdb,
            ]);

            $pdf->save(public_path('storage/document_laporan_pdf_ppdb/' . $ppdb->name . '.pdf'));
        }
        // End PDF

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_ppdb');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Laporan pdf ppdb tidak ada!');
        }

        $zipFileName = 'laporan_pdf_ppdb_tahun_' . $saveYearFromCall . '.zip';
        $createZip = $this->ppdbService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadLaporanPpdbPdf($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$dataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        $pdf = DomPDF::loadView('ppdb::layouts.admin.pdf', [
            'ppdb' => $dataPpdb
        ]);

        return $pdf->download('laporan pdf ppdb ' . $dataPpdb->name . '.pdf');
    }

    public function downloadZipLaporanPpdbExcel($saveYearFromCall)
    {
        // Star EXCEL
        $listPpdb =  Ppdb::where('tahun_daftar', $saveYearFromCall)->latest()->get();

        if ($listPpdb->isEmpty()) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Data ppdb tidak ditemukan!');
        }

        foreach ($listPpdb as $ppdb) {
            $fileName = $ppdb['name'] . '.xlsx';

            ExportExcel::store(new ExportPpdb($ppdb->uuid), $fileName, 'public');

            $sourcePath = storage_path('app/public/' . $fileName);
            $destinationPath = storage_path('app/public/document_laporan_excel_ppdb/' . $fileName);

            File::move($sourcePath, $destinationPath);
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_ppdb');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_ppdb_tahun_' . $saveYearFromCall . '.zip';
        $createZip = $this->ppdbService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-ppdb/tahun-daftar/' . $saveYearFromCall)->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadLaporanPpdbExcel($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$dataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        return ExportExcel::download(new ExportPpdb($saveUuidFromCall), 'laporan excel ppdb ' . $dataPpdb->name . '.xlsx');
    }

    public function show($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$dataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        $checkSiswaOrNot = Siswa::where('nisn', $dataPpdb->nisn)->exists();

        return view('ppdb::layouts.admin.show', compact('dataUserAuth', 'dataPpdb', 'checkSiswaOrNot'));
    }

    public function accept($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$dataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        $this->ppdbService->acceptPpdb($saveUuidFromCall);

        return redirect('/data-ppdb/' . $saveUuidFromCall)->with(['success' => 'Peserta ppdb berhasil di terima!']);
    }

    public function edit($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $dataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$dataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        $timeBox = $this->ppdbService->getEditTime();

        return view('ppdb::layouts.admin.edit', compact('dataUserAuth', 'dataPpdb', 'timeBox'));
    }

    public function update(UpdatePpdbRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $this->ppdbService->editPpdb($validateData, $saveUuidFromCall);

        return redirect('/data-ppdb/tahun-daftar/' . $validateData['tahun_daftar'])->with(['success' => 'Data ppdb berhasil di edit!']);
    }

    public function delete($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-ppdb/tahun-daftar')->with('error', 'Data tidak valid!');
        }

        $dataPpdb = Ppdb::where('uuid', $saveUuidFromCall)->first();

        if (!$dataPpdb) {
            return redirect('/data-ppdb/' . $saveUuidFromCall)->with('error', 'Data peserta ini tidak ada!');
        }

        if ($dataPpdb->bukti_pendaftaran !== 'assets/dashboard/img/surat-ppdb.png') {
            File::delete($dataPpdb->bukti_pendaftaran);
        }

        $dataPpdb->delete();

        return redirect('/data-ppdb/tahun-daftar')->with('success', 'Data ppdb sudah berhasil dihapus!');
    }
}
