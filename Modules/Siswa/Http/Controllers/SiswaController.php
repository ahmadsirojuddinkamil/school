<?php

namespace Modules\Siswa\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Modules\Siswa\Entities\Siswa;
use Modules\Siswa\Http\Requests\{
    DownloadExcelActiveRequest,
    UpdateSiswaRequest,
    DownloadExcelGraduatedRequest,
    DownloadPdfActiveRequest,
    DownloadPdfGraduatedRequest
};
use Modules\Siswa\Services\SiswaService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Siswa\Exports\{ExportSiswaActive, ExportSiswaGraduated};

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

        return view('siswa::pages.siswa.status', compact('dataUserAuth', 'getStatusSiswa'));
    }

    public function getListClassSiswa()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $getListClassSiswa = $this->siswaService->getListSiswaClass();

        if (!$getListClassSiswa) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::pages.siswa.list_class', compact('dataUserAuth', 'getListClassSiswa'));
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

        return view('siswa::pages.siswa.show_class', compact('dataUserAuth', 'saveClassFromRoute', 'getListSiswa'));
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

        return view('siswa::pages.siswa.show_active', compact('dataUserAuth', 'getDataSiswa'));
    }

    public function downloadPdfSiswaActive(DownloadPdfActiveRequest $request)
    {
        $validateData = $request->validated();

        if (!is_numeric($validateData['kelas']) || !in_array($validateData['kelas'], ['10', '11', '12'])) {
            return redirect()->route('siswa.status')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $getDataSiswaActive = Siswa::where('kelas', $validateData['kelas'])->latest()->get();
        $totalSiswaActive = $getDataSiswaActive->count();

        if ($getDataSiswaActive->isEmpty()) {
            return abort(404);
        }

        $pdf = DomPDF::loadView('siswa::layouts.siswa.pdf_active', [
            'dataSiswaActive' => $getDataSiswaActive,
            'totalSiswaActive' => $totalSiswaActive,
        ]);

        return $pdf->download('laporan pdf siswa kelas ' . $validateData['kelas'] . '.pdf');
    }

    public function downloadExcelSiswaActive(DownloadExcelActiveRequest $request)
    {
        $validateData = $request->validated();

        if (!is_numeric($validateData['kelas']) || !in_array($validateData['kelas'], ['10', '11', '12'])) {
            return redirect()->route('siswa.status')->with(['error' => 'Kelas tidak di temukan!']);
        }

        $getDataSiswaActive = Siswa::where('kelas', $validateData['kelas'])->latest()->get();

        if ($getDataSiswaActive->isEmpty()) {
            return abort(404);
        }

        return ExportExcel::download(new ExportSiswaActive($validateData['kelas']), 'laporan siswa aktif kelas ' . $validateData['kelas'] . '.xlsx');
    }

    public function downloadPdfSiswaGraduated(DownloadPdfGraduatedRequest $request)
    {
        $validateData = $request->validated();
        $this->siswaService->checkValidYear($validateData['tahun_lulus']);

        $getDataSiswaGraduated = Siswa::where('tahun_lulus', $validateData['tahun_lulus'])->latest()->get();
        $totalSiswaGraduated = $getDataSiswaGraduated->count();

        if ($getDataSiswaGraduated->isEmpty()) {
            return abort(404);
        }

        $pdf = DomPDF::loadView('siswa::layouts.siswa.pdf_graduated', [
            'dataSiswaGraduated' => $getDataSiswaGraduated,
            'totalSiswaGraduated' => $totalSiswaGraduated,
        ]);

        return $pdf->download('laporan pdf siswa lulus tahun ' . $validateData['tahun_lulus'] . '.pdf');
    }

    public function downloadExcelSiswaGraduated(DownloadExcelGraduatedRequest $request)
    {
        $validateData = $request->validated();
        $this->siswaService->checkValidYear($validateData['tahun_lulus']);

        $getDataSiswaGraduated = Siswa::where('tahun_lulus', $validateData['tahun_lulus'])->latest()->get();

        if ($getDataSiswaGraduated->isEmpty()) {
            return abort(404);
        }

        return ExportExcel::download(new ExportSiswaGraduated($validateData['tahun_lulus']), 'laporan siswa lulus tahun ' . $validateData['tahun_lulus'] . '.xlsx');
    }

    public function getListSiswaGraduated()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        $getSiswaGraduated = Siswa::whereNotNull('tahun_lulus')->latest()->get();

        if ($getSiswaGraduated->isEmpty()) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa lulus tidak ditemukan!']);
        }

        $getDataSiswaGraduated = Siswa::whereNotNull('tahun_lulus')->pluck('tahun_lulus')->toArray();
        $ListYearGraduated = array_values(array_unique($getDataSiswaGraduated));
        rsort($ListYearGraduated);

        return view('siswa::pages.siswa.graduated', compact('dataUserAuth', 'getSiswaGraduated', 'ListYearGraduated'));
    }

    public function showSiswaGraduated($saveUuidFromRoute)
    {
        $this->siswaService->checkGraduatedUuidOrNot($saveUuidFromRoute);
        $dataUserAuth = $this->userService->getProfileUser();

        $getDataSiswa = Siswa::where('uuid', $saveUuidFromRoute)->first();
        if (!$getDataSiswa) {
            return redirect()->route('siswa.graduated')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        return view('siswa::pages.siswa.show_graduated', compact('dataUserAuth', 'getDataSiswa'));
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

        return view('siswa::pages.siswa.edit', compact('dataUserAuth', 'getDataSiswa', 'timeBox'));
    }

    public function update(UpdateSiswaRequest $request, $saveUuidFromRoute)
    {
        $this->siswaService->checkUpdateUuidOrNot($saveUuidFromRoute);

        $validateData = $request->validated();
        $this->siswaService->updateDataSiswa($validateData, $saveUuidFromRoute);

        return redirect()->route('siswa.status')->with(['success' => 'Data siswa berhasil di edit!']);
    }

    public function delete($saveUuidFromRoute)
    {
        $this->siswaService->checkDeleteUuidOrNot($saveUuidFromRoute);

        $getDataSiswa = Siswa::where('uuid', $saveUuidFromRoute)->first();
        if (!$getDataSiswa) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }

        if ($getDataSiswa->foto !== 'assets/dashboard/img/foto-siswa.png') {
            File::delete($getDataSiswa->foto);
        }

        $getDataSiswa->delete();

        return redirect()->route('siswa.status')->with(['success' => 'Data siswa berhasil di hapus!']);
    }
}
