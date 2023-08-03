<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpdbRequest;
use App\Models\Siswa;
use App\Services\CreatePpdbService;
use DateTime;

class PpdbController extends Controller
{
    public function index()
    {
        $today = new DateTime();
        $minDate = date_modify(clone $today, '-21 years')->format('Y-m-d');
        return view('pages.ppdb.index', compact('today', 'minDate'));
    }

    public function store(StorePpdbRequest $Request, CreatePpdbService $CreatePpdbService)
    {
        $ValidateData = $Request->validated();

        $CheckIfDataAlreadyExists = Siswa::where('email', $ValidateData['email'])
            ->orWhere('nisn', $ValidateData['nisn'])
            ->first();

        if ($CheckIfDataAlreadyExists) {
            return redirect('/ppdb')->with(['error' => 'NISN dan Email sudah terdaftar!']);
        }

        $CreatePpdbService->SaveDataSiswaPpdb($ValidateData);
        return redirect('/ppdb')->with(['success' => 'Data ppdb anda berhasil dikirim! Tolong check email dalam 24 jam']);
    }
}
