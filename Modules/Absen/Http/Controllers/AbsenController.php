<?php

namespace Modules\Absen\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\{StoreAbsenRequest, UserDownloadPdfAbsenRequest};
use Modules\Absen\Services\AbsenService;
use Modules\Siswa\Services\SiswaService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;

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
        $checkAbsenOrNot = null;
        $today = now()->format('Y-m-d');

        if ($dataUserAuth[1] == 'siswa') {
            $latestAbsen = $dataUserAuth[0]->siswa->absens()->latest()->first();

            if ($latestAbsen && $latestAbsen->created_at->format('Y-m-d') == $today) {
                $checkAbsenOrNot = true;
            }
        }

        if ($dataUserAuth[1] == 'guru') {
            $latestAbsen = $dataUserAuth[0]->guru->absens()->latest()->first();

            if ($latestAbsen && $latestAbsen->created_at->format('Y-m-d') == $today) {
                $checkAbsenOrNot = true;
            }
        }

        return view('absen::layouts.create_absen', compact('dataUserAuth', 'checkAbsenOrNot'));
    }

    public function store(StoreAbsenRequest $request)
    {
        $validateData = $request->validated();
        $today = now()->format('Y-m-d');

        if (in_array($validateData['status'], ['10', '11', '12'])) {
            $latestAbsenTime = Auth::user()->siswa->absens()->latest()->value('created_at');

            if ($latestAbsenTime === $today) {
                return redirect('/absen')->with('error', 'Anda sudah melakukan absen!');
            }

            $this->absenService->create($validateData, Auth::user()->siswa->id);
        }

        if ($validateData['status'] == 'guru') {
            $latestAbsenTime = Auth::user()->guru->absens()->latest()->value('created_at');

            if ($latestAbsenTime === $today) {
                return redirect('/absen')->with('error', 'Anda sudah melakukan absen!');
            }

            $this->absenService->create($validateData, Auth::user()->guru->id);
        }

        return redirect('/absen')->with('success', 'Anda berhasil melakukan absen!');
    }

    public function laporan()
    {
        $dataUserAuth = $this->userService->getProfileUser();
        $idRole = null;

        if ($dataUserAuth[1] == 'siswa') {
            $listAbsen = $dataUserAuth[0]->siswa->absens()->latest()->get();
            $idRole = $dataUserAuth[0]->siswa->id;
        }

        if ($dataUserAuth[1] == 'guru') {
            $listAbsen = $dataUserAuth[0]->guru->absens()->latest()->get();
            $idRole = $dataUserAuth[0]->guru->id;
        }

        if ($listAbsen->isEmpty()) {
            return redirect('/absen')->with('error', 'Data absen belum ada!');
        }

        $listKehadiran = $this->absenService->getTotalKeterangan($listAbsen);

        return view('absen::layouts.laporan', compact('dataUserAuth', 'listAbsen', 'idRole', 'listKehadiran'));
    }

    public function userDownloadPdfLaporanAbsen(UserDownloadPdfAbsenRequest $request)
    {
        $validateData = $request->validated();

        if ($validateData['role'] == 'siswa') {
            $listAbsen = Absen::where('siswa_id', $validateData['id'])->latest()->get();
            $listKeterangan = $this->absenService->getTotalKeterangan($listAbsen);
        }

        if ($validateData['role'] == 'guru') {
            $listAbsen = Absen::where('guru_id', $validateData['id'])->latest()->get();
            $listKeterangan = $this->absenService->getTotalKeterangan($listAbsen);
        }

        if ($listAbsen->isEmpty()) {
            return redirect('/absen')->with('error', 'Data absen belum ada!');
        }

        $pdf = DomPDF::loadView('absen::layouts.pdf_user', [
            'listAbsen' => $listAbsen,
            'totalAbsen' => $listKeterangan['totalAbsen'],
            'name' => Auth::user()->name,
            'totalHadir' => $listKeterangan['totalHadir'],
            'totalSakit' => $listKeterangan['totalSakit'],
            'totalAcara' => $listKeterangan['totalAcara'],
            'totalMusibah' => $listKeterangan['totalMusibah'],
            'totalTidakHadir' => $listKeterangan['totalTidakHadir'],
        ]);

        return $pdf->download('laporan pdf absen ' . Auth::user()->name . '.pdf');
    }
}
