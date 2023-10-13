<?php

namespace Modules\Ppdb\Http\Controllers;

use App\Services\UserService;
use Barryvdh\DomPDF\Facade\Pdf as DomPDF;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel as ExportExcel;
use Modules\Ppdb\Entities\{OpenPpdb, Ppdb};
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

    public function deleteOpenPpdb()
    {
        OpenPpdb::first()->delete();

        return redirect()->route('ppdb.year')->with(['success' => 'Berhasil menutup pendaftaran ppdb!']);
    }

    public function showYear($saveYearFromRoute)
    {
        $this->ppdbService->checkValidYear($saveYearFromRoute);

        $dataUserAuth = $this->userService->getProfileUser();
        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromRoute)->latest()->get();
        $totalDataPpdb = $getDataPpdb->count();

        if ($getDataPpdb->isEmpty()) {
            return abort(404);
        }

        return view('ppdb::layouts.admin.show_year', compact('dataUserAuth', 'getDataPpdb', 'totalDataPpdb', 'saveYearFromRoute'));
    }

    public function downloadPdf($saveYearFromRoute)
    {
        $this->ppdbService->checkValidYear($saveYearFromRoute);

        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromRoute)->latest()->get();
        $totalDataPpdb = $getDataPpdb->count();

        if ($getDataPpdb->isEmpty()) {
            return abort(404);
        }

        $pdf = DomPDF::loadView('ppdb::layouts.admin.pdf', [
            'getDataPpdb' => $getDataPpdb,
            'totalDataPpdb' => $totalDataPpdb,
        ]);

        return $pdf->download('example.pdf');
    }

    public function downloadExcel($saveYearFromRoute)
    {
        $this->ppdbService->checkValidYear($saveYearFromRoute);

        $getDataPpdb = Ppdb::where('tahun_daftar', $saveYearFromRoute)->latest()->get();

        if ($getDataPpdb->isEmpty()) {
            return abort(404);
        }

        return ExportExcel::download(new ExportPpdb($saveYearFromRoute), 'laporan ppdb tahun ' . $saveYearFromRoute . '.xlsx');
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

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $getDataUserPpdb->tahun_daftar])->with(['success' => 'Peserta ppdb berhasil menjadi siswa!']);
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

        File::delete($getDataUserPpdb->bukti_pendaftaran);
        $getDataUserPpdb->delete();

        return redirect()->route('ppdb.year.show', ['save_year_from_event' => $getDataUserPpdb->tahun_daftar])->with('success', 'Data ppdb sudah berhasil dihapus!');
    }
}
