<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $dataUserAuth = Session::get('userData');

        return view('dashboard::layouts.index', compact('dataUserAuth'));
    }
}
