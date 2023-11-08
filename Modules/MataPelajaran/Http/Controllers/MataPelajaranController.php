<?php

namespace Modules\MataPelajaran\Http\Controllers;

use App\Services\UserService;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MataPelajaranController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function listMataPelajaran()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        return view('matapelajaran::layouts.Admin.list', compact('dataUserAuth'));
    }
}
