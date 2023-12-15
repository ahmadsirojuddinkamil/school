<?php

namespace Modules\MataPelajaran\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;
use Modules\MataPelajaran\Http\Requests\{StoreMataPelajaranRequest, UpdateMataPelajaranRequest};
use Modules\MataPelajaran\Services\MataPelajaranService;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\MataPelajaran\Exports\ExportExcelMapel;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

use function PHPUnit\Framework\isEmpty;

class MataPelajaranController extends Controller
{
    protected $mataPelajaranService;

    public function __construct(MataPelajaranService $saveDataFromCall)
    {
        $this->mataPelajaranService = $saveDataFromCall;
    }

    public function list()
    {
        $dataUserAuth = Session::get('userData');
        $listMataPelajaran = MataPelajaran::with('guru')->latest()->get();

        return view('matapelajaran::layouts.Admin.list', compact('dataUserAuth', 'listMataPelajaran'));
    }

    public function create()
    {
        $dataUserAuth = Session::get('userData');
        $listNameGuru = Guru::whereNull('mata_pelajaran_uuid')->pluck('name');

        return view('matapelajaran::layouts.Admin.create', compact('dataUserAuth', 'listNameGuru'));
    }

    public function store(StoreMataPelajaranRequest $request)
    {
        $validateData = $request->validated();
        $this->mataPelajaranService->createMapel($validateData);
        return redirect('/data-mata-pelajaran')->with('success', 'Mata pelajaran ' . $validateData['name'] . ' berhasil ditambahkan!');
    }

    public function downloadAllMapel($saveNameFromCall)
    {
        if (!in_array($saveNameFromCall, ['pdf', 'excel'])) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $dataAllMapel = MataPelajaran::latest()->get();

        if ($dataAllMapel->isEmpty()) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak ditemukan!');
        }

        if ($saveNameFromCall === 'pdf') {
            $randomString = Str::random(8);
            $randomNumber = rand(1000, 9999);
            $pdfFolderPath = public_path('storage/document_mata_pelajaran_temporary/data pdf all mapel_' . $randomString . $randomNumber);

            File::makeDirectory($pdfFolderPath, 0777, true);
            $this->mataPelajaranService->createPdfAllMapel($dataAllMapel, $pdfFolderPath);

            $downloadZip = $this->mataPelajaranService->downloadZipAllMapelPdf($pdfFolderPath);
            return $downloadZip[0]->download(public_path($downloadZip[1]))->deleteFileAfterSend(true);
        }

        if ($saveNameFromCall === 'excel') {
            $listFileName = $this->mataPelajaranService->createExcelDataMapel($dataAllMapel);
        }
    }

    public function show($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $dataUserAuth = Session::get('userData');

        $dataMataPelajaran = MataPelajaran::with('guru')->where('uuid', $saveUuidFromCall)->first();

        if (!$dataMataPelajaran) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak di temukan!');
        }

        return view('matapelajaran::layouts.Admin.show', compact('dataUserAuth', 'dataMataPelajaran'));
    }

    public function downloadFullDataMateri($saveNameFromCall, $saveUuidFromCall)
    {
        if (!in_array($saveNameFromCall, ['pdf', 'excel'])) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $dataMapel = MataPelajaran::where('uuid', $saveUuidFromCall)->first();

        if (!$dataMapel) {
            return redirect('/data-mata-pelajaran')->with('error', 'File materi tidak ditemukan!');
        }

        if ($saveNameFromCall === 'pdf') {
            $fileNamePdfOrPpt = $this->mataPelajaranService->createSchedulePdfMapel($dataMapel);
        }

        if ($saveNameFromCall === 'excel') {
            $randomString = Str::random(8);
            $randomNumber = rand(1000, 9999);

            $fileExcelName = 'Jadwal mapel ' . $dataMapel->name . '_' . $randomString . $randomNumber . '.xlsx';
            ExportExcel::store(new ExportExcelMapel($dataMapel->uuid), $fileExcelName, 'public');

            $sourcePath = storage_path('app/public/' . $fileExcelName);
            $destinationPath = storage_path('app/public/document_mata_pelajaran_temporary/' . $fileExcelName);

            File::move($sourcePath, $destinationPath);

            $fileNamePdfOrPpt = $fileExcelName;
        }

        $pathFileMateriPdf = $this->mataPelajaranService->getMateriMapel($dataMapel['materi_pdf'] ?? null);
        $pathFileMateriPpt = $this->mataPelajaranService->getMateriMapel($dataMapel['materi_ppt'] ?? null);
        $pathYoutubeLink = $dataMapel['video'] ?? null;

        $zip = new ZipArchive;
        $zipType = ($saveNameFromCall === 'pdf') ? 'pdf' : 'excel';
        $zipFileName = 'Data ' . $zipType . ' mapel ' . $dataMapel->name;

        $zip->open($zipFileName, ZipArchive::CREATE);
        $zip->addEmptyDir('Data mapel ' . $dataMapel->name);

        $folderStoragePath = public_path('storage/document_mata_pelajaran_temporary/' . $fileNamePdfOrPpt);

        $fileExtension = pathinfo($fileNamePdfOrPpt, PATHINFO_EXTENSION);
        $originalFileName = preg_replace('/_[^_]+(?=\.' . preg_quote($fileExtension, '/') . '$)/', '', $fileNamePdfOrPpt);
        $relativePath = 'Data mapel ' . $dataMapel->name . '/' . $originalFileName;

        $zip->addFile($folderStoragePath, $relativePath);

        if ($pathFileMateriPdf !== null) {
            $this->mataPelajaranService->addFileToZip($zip, $pathFileMateriPdf, 'Materi pdf.zip', $dataMapel->name);
        }

        if ($pathFileMateriPpt !== null) {
            $this->mataPelajaranService->addFileToZip($zip, $pathFileMateriPpt, 'Materi ppt.zip', $dataMapel->name);
        }

        if ($pathYoutubeLink !== null) {
            $this->mataPelajaranService->addStringToZip($zip, $pathYoutubeLink, 'Link youtube.txt', $dataMapel->name);
        }

        $zip->close();

        unlink($folderStoragePath);

        return response()->download($zipFileName)->deleteFileAfterSend(true);
    }

    public function downloadMateriPdf($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $materiPdf = MataPelajaran::where('uuid', $saveUuidFromCall)->first();

        if (!$materiPdf || !$materiPdf->materi_pdf) {
            return redirect('/data-mata-pelajaran')->with('error', 'File materi PDF tidak ditemukan!');
        }

        $filePath = public_path($materiPdf['materi_pdf']);

        $fileName = pathinfo($filePath)['filename'];

        $downloadFileName = "{$fileName}.zip";

        return response()->download($filePath, $downloadFileName);
    }

    public function downloadMateriPpt($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $materiPpt = MataPelajaran::where('uuid', $saveUuidFromCall)->first();

        if (!$materiPpt || !$materiPpt->materi_ppt) {
            return redirect('/data-mata-pelajaran')->with('error', 'File materi PPT tidak ditemukan!');
        }

        $filePath = public_path($materiPpt['materi_ppt']);

        $fileName = pathinfo($filePath)['filename'];

        $downloadFileName = "{$fileName}.zip";

        return response()->download($filePath, $downloadFileName);
    }

    public function edit($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $dataUserAuth = Session::get('userData');

        $dataMataPelajaran = MataPelajaran::with('guru')->where('uuid', $saveUuidFromCall)->first();

        if (!$dataMataPelajaran) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak di temukan!');
        }

        $listNameGuru = Guru::pluck('name');

        return view('matapelajaran::layouts.Admin.edit', compact('dataUserAuth', 'dataMataPelajaran', 'listNameGuru'));
    }

    public function update(UpdateMataPelajaranRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $dataMataPelajaran = MataPelajaran::where('uuid', $saveUuidFromCall)->first();

        if (!$dataMataPelajaran) {
            return redirect('/data-mata-pelajaran')->with(['error' => 'Data mata pelajaran tidak ditemukan!']);
        }

        $this->mataPelajaranService->updateMapel($validateData, $dataMataPelajaran);

        return redirect('/data-mata-pelajaran')->with('success', 'Mata pelajaran ' . $dataMataPelajaran->name . ' berhasil di update!');
    }

    public function delete($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return redirect('/data-mata-pelajaran')->with('error', 'Data mata pelajaran tidak valid!');
        }

        $dataMataPelajaran = MataPelajaran::where('uuid', $saveUuidFromCall)->first();

        if (!$dataMataPelajaran) {
            return redirect('/data-mata-pelajaran')->with(['error' => 'Data mata pelajaran tidak ditemukan!']);
        }

        $this->mataPelajaranService->deleteMapel($dataMataPelajaran);

        return redirect('/data-mata-pelajaran')->with('success', 'Mata pelajaran ' . $dataMataPelajaran->name . ' berhasil di hapus!');
    }
}
