<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePpdbRequest;
use App\Services\CreatePpdbService;

class PpdbController extends Controller
{
    public function index()
    {
        return view('pages.ppdb.index');
    }

    public function store(StorePpdbRequest $Request, CreatePpdbService $CreatePpdbService)
    {
        $ValidateData = $Request->validated();

        $CreatePpdbService->SaveDataSiswaPpdb($ValidateData);

        return redirect('/ppdb')->with([
            'success' => 'Anda berhasil mendaftar ppdb',
        ]);
    }
}
