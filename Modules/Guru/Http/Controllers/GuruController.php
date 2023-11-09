<?php

namespace Modules\Guru\Http\Controllers;

use Illuminate\Routing\Controller;
use Modules\Guru\Entities\Guru;
use Modules\Guru\Http\Requests\{StoreGuruRequest, UpdateBiodataGuruRequest, UpdateTeachingHoursRequest};
use Modules\Guru\Services\GuruService;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Guru\Exports\{ExportExcelBiodataGuru, ExportExcelListGuru};

class GuruController extends Controller
{
    protected $guruService;

    public function __construct(GuruService $guruService)
    {
        $this->guruService = $guruService;
    }

    public function listGuru()
    {
        $dataUserAuth = Session::get('userData');
        $dataGuru = Guru::latest()->get();

        return view('guru::layouts.Admin.list_guru', compact('dataUserAuth', 'dataGuru'));
    }

    public function show($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $dataUserAuth = Session::get('userData');

        if ($dataUserAuth[1] == 'guru') {
            $guru = $dataUserAuth[0]->load('guru')->guru;
            if ($guru->uuid != $saveUuidFromCall) {
                return redirect('/data-guru/' . $guru->uuid)->with('error', 'Data guru tidak valid!');
            }
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-guru')->with('error', 'Data guru tidak ditemukan!');
        }

        return view('guru::layouts.show', compact('dataUserAuth', 'dataGuru'));
    }

    public function create()
    {
        $dataUserAuth = Session::get('userData');

        return view('guru::layouts.Admin.create', compact('dataUserAuth'));
    }

    public function store(StoreGuruRequest $request)
    {
        $validateData = $request->validated();

        $createGuru = $this->guruService->createGuru($validateData);

        if (!$createGuru) {
            return redirect('/data-guru')->with('error', 'Data guru gagal dibuat!');
        }

        return redirect('/data-guru')->with('success', 'Data guru berhasil dibuat!');
    }

    public function downloadZipListGuruPdf()
    {
        // Star PDF
        $listGuru = Guru::latest()->get();

        if ($listGuru->isEmpty()) {
            return redirect('/data-guru')->with('error', 'Data guru tidak ditemukan!');
        }

        foreach ($listGuru as $guru) {
            $pdf = DomPDF::loadView('guru::components.pdf_biodata_guru', [
                'biodata' => $guru,
            ]);
            $pdf->save(public_path('storage/document_laporan_pdf_guru/' . $guru->name . '.pdf'));
        }
        // End PDF

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-guru')->with('error', 'Laporan pdf tidak ditemukan!');
        }
        $zipFileName = 'laporan_pdf_guru.zip';
        $createZip = $this->guruService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-guru')->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadZipListGuruExcel()
    {
        // Star EXCEL
        $listGuru = Guru::latest()->get();

        if ($listGuru->isEmpty()) {
            return redirect('/data-guru')->with('error', 'Data guru tidak ditemukan!');
        }

        foreach ($listGuru as $guru) {
            $fileName = $guru['name'] . '.xlsx';

            ExportExcel::store(new ExportExcelListGuru($guru->uuid), $fileName, 'public');

            $sourcePath = storage_path('app/public/' . $fileName);
            $destinationPath = storage_path('app/public/document_laporan_excel_guru/' . $fileName);

            File::move($sourcePath, $destinationPath);
        }
        // End EXCEL

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_excel_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-guru')->with('error', 'Laporan excel tidak ditemukan!');
        }

        $zipFileName = 'laporan_excel_guru.zip';
        $createZip = $this->guruService->createZip($zipFileName, $folderPath);

        if (!$createZip) {
            return redirect('/data-guru')->with('error', 'Gagal membuat laporan zip!');
        }

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function downloadPdfBiodataGuru($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $dataUserAuth = Session::get('userData');

        if ($dataUserAuth[1] == 'guru') {
            $guru = $dataUserAuth[0]->load('guru')->guru;
            if ($guru->uuid != $saveUuidFromCall) {
                return redirect('/data-guru/' . $guru->uuid)->with('error', 'Data guru tidak valid!');
            }
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-guru/' . $saveUuidFromCall)->with('error', 'Biodata tidak ditemukan!');
        }

        $pdf = DomPDF::loadView('guru::components.pdf_biodata_guru', [
            'biodata' => $dataGuru,
        ]);

        return $pdf->download('laporan pdf ' . $dataGuru['name'] . '.pdf');
    }

    public function downloadExcelBiodataGuru($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-guru')->with('error', 'Data guru tidak ditemukan!');
        }

        return ExportExcel::download(new ExportExcelBiodataGuru($saveUuidFromCall), 'laporan excel ' . $dataGuru['name'] . '.xlsx');
    }

    public function edit($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $dataUserAuth = Session::get('userData');

        if ($dataUserAuth[1] == 'guru') {
            $guru = $dataUserAuth[0]->load('guru')->guru;
            if ($guru->uuid != $saveUuidFromCall) {
                return redirect('/data-guru/' . $guru->uuid)->with('error', 'Data guru tidak valid!');
            }
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-guru')->with('error', 'Data guru tidak ditemukan!');
        }

        return view('guru::layouts.edit', compact('dataUserAuth', 'dataGuru'));
    }

    public function update(UpdateBiodataGuruRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $dataUserAuth = Session::get('userData');

        if ($dataUserAuth[1] == 'guru') {
            $guru = $dataUserAuth[0]->load('guru')->guru;
            if ($guru->uuid != $saveUuidFromCall) {
                return redirect('/data-guru/' . $guru->uuid)->with('error', 'Data guru tidak valid!');
            }
        }

        $this->guruService->updateGuru($validateData, $saveUuidFromCall);

        if ($dataUserAuth[1] == 'guru') {
            return redirect('/data-guru/' . $saveUuidFromCall)->with(['success' => 'Data anda berhasil di edit!']);
        }

        return redirect('/data-guru')->with(['success' => 'Data guru berhasil di edit!']);
    }

    public function updateTeachingHours(UpdateTeachingHoursRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $this->guruService->updateTeachingHours($validateData, $saveUuidFromCall);

        return redirect('/data-guru')->with(['success' => 'Jam mengajar guru berhasil di edit!']);
    }

    public function delete($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-guru')->with('error', 'Data guru tidak valid!');
        }

        $dataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$dataGuru) {
            return redirect('/data-guru')->with(['error' => 'Data guru gagal di hapus!']);
        }

        if ($dataGuru->foto !== 'assets/dashboard/img/foto-guru.png') {
            File::delete($dataGuru->foto);
        }

        $dataGuru->delete();

        return redirect('/data-guru')->with(['success' => 'Data guru berhasil di hapus!']);
    }
}
