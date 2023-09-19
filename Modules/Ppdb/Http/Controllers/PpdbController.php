<?php

namespace Modules\Ppdb\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use DateTime;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\{DB, File};
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Ppdb\Entities\OpenPpdb;
use Modules\Ppdb\Entities\Ppdb;
use Modules\Ppdb\Exports\ExportPpdb;
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
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');
        $minDate = date_modify(clone $today, '-21 years')->format('Y-m-d');
        $dataUserAuth = $this->userService->getProfileUser();

        return view('ppdb::pages.ppdb.register', compact('todayDate', 'minDate', 'dataUserAuth'));
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

        $yearTotals = [];
        $previousYear = null;
        $total = 0;

        foreach ($allYears as $year) {
            if ($previousYear === null) {
                $previousYear = $year;
                $total = 1;
            } elseif ($previousYear == $year) {
                $total++;
            } else {
                $yearTotals[$previousYear] = ['key' => $previousYear, 'value' => $total];
                $previousYear = $year;
                $total = 1;
            }
        }

        if ($previousYear !== null) {
            $yearTotals[$previousYear] = ['key' => $previousYear, 'value' => $total];
        }

        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');
        $maxDate = date_modify(clone $today, '+7 days')->format('Y-m-d');

        $findOpenOrClosePpdb = OpenPpdb::first();

        return view('ppdb::pages.ppdb.year', compact('dataUserAuth', 'yearTotals', 'todayDate', 'maxDate', 'findOpenOrClosePpdb'));
    }

    public function openPpdb(OpenOrClosePpdbRequest $request)
    {
        $validateData = $request->validated();

        $this->ppdbService->openPpdb($validateData);

        return redirect()->route('ppdb.year')->with(['success' => 'Berhasil membuka pendaftaran ppdb!']);
    }

    public function deleteOpenPpdb()
    {
        OpenPpdb::first()->delete();

        return redirect()->route('ppdb.year')->with(['success' => 'Berhasil menutup pendaftaran ppdb!']);
    }

    public function showYear($saveYearFromRoute)
    {
        // helper
        $validateData = isValidYear($saveYearFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromRoute)->latest()->get();
        $totalDataPpdb = $getDataPpdb->count();

        if ($getDataPpdb->isEmpty()) {
            return abort(404);
        }

        $dataUserAuth = $this->userService->getProfileUser();

        return view('ppdb::pages.ppdb.show_year', compact('getDataPpdb', 'dataUserAuth', 'totalDataPpdb', 'saveYearFromRoute'));
    }

    public function downloadPdf($saveYearFromRoute)
    {
        // helper
        $validateData = isValidYear($saveYearFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromRoute)->latest()->get();
        $totalDataPpdb = $getDataPpdb->count();

        if ($getDataPpdb->isEmpty()) {
            return abort(404);
        }

        $pdf = DomPDF::loadView('ppdb::layouts.ppdb.pdf', [
            'getDataPpdb' => $getDataPpdb,
            'totalDataPpdb' => $totalDataPpdb,
        ]);

        return $pdf->download('example.pdf');
    }

    public function downloadExcel($saveYearFromRoute)
    {
        // helper
        $validateData = isValidYear($saveYearFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromRoute)->latest()->get();
        if ($getDataPpdb->isEmpty()) {
            return abort(404);
        }

        return ExportExcel::download(new ExportPpdb($saveYearFromRoute), 'laporan ppdb tahun ' . $saveYearFromRoute . '.xlsx');
    }

    public function show($saveUuidFromRoute)
    {
        $validateData = isValidUuid($saveUuidFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $getDataUserPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        if (!$getDataUserPpdb) {
            return abort(404);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $findSiswa = Siswa::where('nisn', $getDataUserPpdb->nisn)->first();

        return view('ppdb::pages.ppdb.show', compact('getDataUserPpdb', 'dataUserAuth', 'findSiswa'));
    }

    public function accept($saveUuidFromRoute)
    {
        $validateData = isValidUuid($saveUuidFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $findRoomYear = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        if (!$findRoomYear) {
            return abort(404);
        }

        $this->ppdbService->acceptPpdb($saveUuidFromRoute);

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $findRoomYear->tahun_daftar])->with(['success' => 'Peserta ppdb berhasil menjadi siswa!']);
    }

    public function edit($saveUuidFromRoute)
    {
        $validateData = isValidUuid($saveUuidFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $getPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        if (!$getPpdb) {
            return abort(404);
        }

        $dataUserAuth = $this->userService->getProfileUser();
        $today = new DateTime();
        $todayDate = $today->format('Y-m-d');
        $minDate = date_modify(clone $today, '-21 years')->format('Y-m-d');

        return view('ppdb::pages.ppdb.edit', compact('getPpdb', 'dataUserAuth', 'minDate', 'todayDate'));
    }

    public function update(UpdatePpdbRequest $request, $saveUuidFromRoute)
    {
        $validateUuid = isValidUuid($saveUuidFromRoute);
        if (!$validateUuid) {
            return abort(404);
        }

        $validateData = $request->validated();
        $this->ppdbService->editPpdb($validateData, $saveUuidFromRoute);

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $validateData['tahun_daftar']])->with(['success' => 'Data ppdb berhasil di edit!']);
    }

    public function delete($saveUuidFromRoute)
    {
        $validateData = isValidUuid($saveUuidFromRoute);
        if (!$validateData) {
            return abort(404);
        }

        $findRoomYear = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        $getPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        File::delete($getPpdb->bukti_pendaftaran);
        $getPpdb->delete();

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $findRoomYear->tahun_daftar])->with('success', 'Data ppdb sudah berhasil dihapus!');
    }
}
