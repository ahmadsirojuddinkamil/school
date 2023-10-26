<?php

namespace Modules\Guru\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Modules\Guru\Entities\Guru;
use Modules\Guru\Http\Requests\{StoreGuruRequest, UpdateBiodataGuruRequest, UpdateTeachingHoursRequest};
use Modules\Guru\Services\GuruService;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Guru\Exports\{ExportExcelBiodataGuru, ExportExcelListGuru};
use Ramsey\Uuid\Uuid;
use ZipArchive;

class GuruController extends Controller
{
    protected $userService;
    protected $guruService;

    public function __construct(UserService $userService, GuruService $guruService)
    {
        $this->userService = $userService;
        $this->guruService = $guruService;
    }

    public function listGuru()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $getDataGuru = Guru::latest()->get();

        return view('guru::layouts.Admin.list_guru', compact('dataUserAuth', 'getDataGuru'));
    }

    public function biodata($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with('error', 'Biodata guru tidak ditemukan!');
        }

        return view('guru::layouts.biodata', compact('dataUserAuth', 'biodataGuru'));
    }

    public function create()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        return view('guru::layouts.Admin.create', compact('dataUserAuth'));
    }

    public function store(StoreGuruRequest $request)
    {
        $validateData = $request->validated();

        $existsGuruOrNot = Guru::where('nuptk', $validateData['nuptk'])->first();

        if ($existsGuruOrNot) {
            return redirect('/data-guru')->with('error', 'Data guru sudah ada!');
        }

        $user = User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => $validateData['nuptk'],
        ]);
        $user->assignRole('guru');

        $changeFotoGuruPath = $validateData['foto']->store('public/document_foto_resmi_guru');
        $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoGuruPath);

        Guru::create([
            'user_id' => $user->id,
            'mata_pelajaran_id' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'nuptk' => $validateData['nuptk'],
            'nip' => $validateData['nip'] ?? null,
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            // 'mata_pelajaran' => $validateData['mata_pelajaran'],
            'agama' => $validateData['agama'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'status_perkawinan' => $validateData['status_perkawinan'],
            'jam_mengajar' => $validateData['jam_mengajar'],
            'pendidikan_terakhir' => $validateData['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $validateData['nama_tempat_pendidikan'],
            'ipk' => $validateData['ipk'],
            'tahun_lulus' => $validateData['tahun_lulus'],
            'alamat_rumah' => $validateData['alamat_rumah'],
            'provinsi' => $validateData['provinsi'],
            'kecamatan' => $validateData['kecamatan'],
            'kelurahan' => $validateData['kelurahan'],
            'kode_pos' => $validateData['kode_pos'],
            'email' => $validateData['email'],
            'no_telpon' => $validateData['no_telpon'],
            'tahun_daftar' => $validateData['tahun_daftar'],
            'tahun_keluar' => $validateData['tahun_keluar'],
            'foto' => $changePublicToStoragePath,
            'nama_bank' => $validateData['nama_bank'] ?? null,
            'nama_buku_rekening' => $validateData['nama_buku_rekening'] ?? null,
            'no_rekening' => $validateData['no_rekening'] ?? null,
        ]);

        return redirect('/data-guru')->with('success', 'Data guru berhasil dibuat!');
    }

    public function downloadPdfBiodataGuru($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with('error', 'Biodata tidak ditemukan!');
        }

        $pdf = DomPDF::loadView('guru::components.pdf_biodata_guru', [
            'biodata' => $biodataGuru,
        ]);

        return $pdf->download('biodata pdf guru ' . $biodataGuru['name'] . '.pdf');
    }

    public function downloadZipListGuruPdf()
    {
        // Star PDF
        $listGuru = Guru::latest()->get();

        if ($listGuru->isEmpty()) {
            return redirect('/data-guru')->with('error', 'Biodata guru tidak ditemukan!');
        }

        foreach ($listGuru as $guru) {
            $pdf = DomPDF::loadView('guru::components.pdf_biodata_guru', [
                'biodata' => $guru,
            ]);
            $pdf->save(public_path('storage/document_laporan_pdf_biodata_guru/' . $guru->name . '.pdf'));
        }
        // End PDF

        // Star ZIP
        $folderPath = public_path('storage/document_laporan_pdf_biodata_guru');
        $fileCount = count(array_diff(scandir($folderPath), ['.', '..']));

        if ($fileCount < 1) {
            return redirect('/data-guru')->with('error', 'Laporan pdf tidak ditemukan!');
        }

        $zipFileName = 'pdf_daftar_biodata_guru.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-guru')->with(['error' => 'Gagal membuat arsip ZIP']);
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

    public function downloadExcelBiodataGuru($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with('error', 'Biodata tidak ditemukan!');
        }

        return ExportExcel::download(new ExportExcelBiodataGuru($saveUuidFromCall), 'biodata ' . $biodataGuru['name'] . 'guru ' . $biodataGuru['mata_pelajaran'] . '.xlsx');
    }

    public function downloadZipListGuruExcel()
    {
        // Star EXCEL
        $listGuru = Guru::latest()->get();

        if ($listGuru->isEmpty()) {
            return redirect('/data-guru')->with('error', 'Biodata guru tidak ditemukan!');
        }

        foreach ($listGuru as $guru) {
            $fileName = 'biodata ' . $guru['name'] . 'guru ' . $guru['mata_pelajaran'] . '.xlsx';

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

        $zipFileName = 'excel_daftar_biodata_guru.zip';
        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) !== true) {
            return redirect('/data-guru')->with(['error' => 'Gagal membuat arsip ZIP']);
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
        // File::deleteDirectory($folderPath);
        // File::makeDirectory($folderPath, 0755, true, true);

        return response()->download($zipFileName)->deleteFileAfterSend(true);
        // End ZIP
    }

    public function edit($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with('error', 'Biodata guru tidak ditemukan!');
        }

        return view('guru::layouts.edit', compact('dataUserAuth', 'biodataGuru'));
    }

    public function update(UpdateBiodataGuruRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $this->guruService->updateBiodataGuru($validateData, $saveUuidFromCall);

        $dataUserAuth = $this->userService->getProfileUser();
        if ($dataUserAuth[1] == 'guru') {
            return redirect('/data-guru/' . $saveUuidFromCall)->with(['success' => 'Biodata anda berhasil di edit!']);
        }

        return redirect('/data-guru')->with(['success' => 'Biodata guru berhasil di edit!']);
    }

    public function updateTeachingHours(UpdateTeachingHoursRequest $request, $saveUuidFromCall)
    {
        $validateData = $request->validated();

        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $this->guruService->updateTeachingHours($validateData, $saveUuidFromCall);

        return redirect('/data-guru')->with(['success' => 'Jam mengajar guru berhasil di edit!']);
    }

    public function delete($saveUuidFromCall)
    {
        if (!preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCall)) {
            return abort(404);
        }

        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with(['error' => 'Biodata guru gagal di hapus!']);
        }

        if ($biodataGuru->foto !== 'assets/dashboard/img/foto-siswa.png') {
            File::delete($biodataGuru->foto);
        }

        $biodataGuru->delete();

        return redirect('/data-guru')->with(['success' => 'Biodata guru berhasil di hapus!']);
    }
}
