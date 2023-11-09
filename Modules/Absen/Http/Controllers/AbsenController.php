<?php

namespace Modules\Absen\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Absen\Entities\Absen;
use Modules\Absen\Http\Requests\{StoreAbsenRequest, UserDownloadPdfAbsenRequest};
use Modules\Absen\Services\AbsenService;
use Modules\Siswa\Services\SiswaService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Support\Facades\Session;

class AbsenController extends Controller
{
    protected $absenService;
    protected $siswaService;

    public function __construct(AbsenService $absenService, SiswaService $siswaService)
    {
        $this->absenService = $absenService;
        $this->siswaService = $siswaService;
    }

    public function absen()
    {
        $dataUserAuth = Session::get('userData');
        $today = now()->format('Y-m-d');

        $latestAbsen = null;

        if ($dataUserAuth[1] == 'siswa' || $dataUserAuth[1] == 'guru') {
            $role = $dataUserAuth[1];
            $profileData = $dataUserAuth[0]->load("$role.absens");
            $latestAbsen = $profileData->$role->absens()->latest()->first();
        }

        $checkAbsenOrNot = $latestAbsen && $latestAbsen->created_at->format('Y-m-d') == $today;

        return view('absen::layouts.create_absen', compact('dataUserAuth', 'checkAbsenOrNot'));
    }

    public function store(StoreAbsenRequest $request)
    {
        $validateData = $request->validated();
        $dataUserAuth = Session::get('userData');
        $today = now()->format('Y-m-d');
        $role = $dataUserAuth[1];

        if (in_array($validateData['status'], ['10', '11', '12', 'guru'])) {
            $latestAbsenTime = $dataUserAuth[0]->load("{$role}.absens")->{"{$role}"}
                ->absens()->latest()->value('created_at');

            if ($latestAbsenTime === $today) {
                return redirect('/absen')->with('error', 'Anda sudah melakukan absen!');
            }

            $this->absenService->create($validateData, $dataUserAuth[0]->load($role)->{$role}->uuid);
        }

        return redirect('/absen')->with('success', 'Anda berhasil melakukan absen!');
    }

    public function laporan()
    {
        $dataUserAuth = Session::get('userData');
        $role = $dataUserAuth[1];
        $uuidRole = null;

        if (in_array($role, ['siswa', 'guru'])) {
            $listAbsen = $dataUserAuth[0]->load("{$role}.absens")->{"{$role}"}
                ->absens()->latest()->get();
            $uuidRole = $dataUserAuth[0]->load($role)->{$role}->uuid;
        }

        if ($listAbsen->isEmpty()) {
            return redirect('/absen')->with('error', 'Data absen belum ada!');
        }

        $listKehadiran = $this->absenService->totalKeterangan($listAbsen);

        return view('absen::layouts.laporan', compact('dataUserAuth', 'listAbsen', 'uuidRole', 'listKehadiran'));
    }

    public function userDownloadPdfLaporanAbsen(UserDownloadPdfAbsenRequest $request)
    {
        $validateData = $request->validated();
        $role = $validateData['role'];
        $uuid = $validateData['uuid'];

        $listAbsen = Absen::where("{$role}_uuid", $uuid)->latest()->get();

        if ($listAbsen->isEmpty()) {
            return redirect('/absen')->with('error', 'Data absen belum ada!');
        }

        $listKeterangan = $this->absenService->totalKeterangan($listAbsen);

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
