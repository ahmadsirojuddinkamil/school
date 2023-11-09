<?php

namespace Modules\MataPelajaran\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class MataPelajaranController extends Controller
{
    public function __construct()
    {
    }

    public function listMataPelajaran()
    {
        $dataUserAuth = Session::get('userData');

        return view('matapelajaran::layouts.Admin.list', compact('dataUserAuth'));
    }
}
