<?php

namespace App\Http\Controllers;

use App\Http\Requests\{StorePpdbRequest, UpdatePpdbRequest};
use App\Models\{Ppdb, Siswa};
use App\Services\{UserService, PpdbService};
use DateTime;
use Illuminate\Support\Facades\File;

class PpdbController extends Controller
{
    protected $userService;
    protected $ppdbService;

    public function __construct(UserService $userService, PpdbService $ppdbService)
    {
        $this->userService = $userService;
        $this->ppdbService = $ppdbService;
    }

    public function index()
    {
        $today = new DateTime();
        $minDate = date_modify(clone $today, '-21 years')->format('Y-m-d');
        $dataUserAuth = $this->userService->getProfileUser();

        return view('pages.ppdb.index', compact('today', 'minDate', 'dataUserAuth'));
    }

    public function store(StorePpdbRequest $Request)
    {
        $validateData = $Request->validated();

        $checkIfDataAlreadyExists = Siswa::where('email', $validateData['email'])
            ->orWhere('nisn', $validateData['nisn'])
            ->first();

        if ($checkIfDataAlreadyExists) {
            return redirect('/ppdb')->with(['error' => 'NISN dan Email sudah terdaftar!']);
        }

        $this->ppdbService->saveDataSiswaPpdb($validateData);

        return redirect('/ppdb')->with(['success' => 'Data ppdb anda berhasil dikirim! Tolong check email dalam 24 jam']);
    }

    public function list()
    {
        $getDataPpdb = Ppdb::with('payments')->latest()->get();
        $dataUserAuth = $this->userService->getProfileUser();

        return view('pages.dashboard.partials.ppdb.index', compact('getDataPpdb', 'dataUserAuth'));
    }

    public function show($saveUuidFromRoute)
    {
        $getDataUserPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        $dataUserAuth = $this->userService->getProfileUser();

        return view('pages.dashboard.partials.ppdb.show', compact('getDataUserPpdb', 'dataUserAuth'));
    }

    public function accept($saveUuidFromRoute)
    {
        $this->ppdbService->acceptPpdb($saveUuidFromRoute);

        return redirect('/ppdb-data')->with(['success' => 'Peserta ppdb berhasil menjadi siswa!']);
    }

    public function edit($saveUuidFromRoute)
    {
        $getPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        $dataUserAuth = $this->userService->getProfileUser();

        return view('pages.dashboard.partials.ppdb.edit', compact('getPpdb', 'dataUserAuth'));
    }

    public function update(UpdatePpdbRequest $request, $saveUuidFromRoute)
    {
        $validateData = $request->validated();
        $this->ppdbService->editPpdb($validateData, $saveUuidFromRoute);

        return redirect('/ppdb-data')->with(['success' => 'Data ppdb berhasil di edit!']);
    }

    public function delete($saveUuidFromRoute)
    {
        $getPpdb = Ppdb::where('uuid', $saveUuidFromRoute)->first();
        File::delete($getPpdb->bukti_pendaftaran);
        $getPpdb->delete();

        return redirect('ppdb-data')->with('success', 'Data ppdb sudah berhasil dihapus!');
    }
}
