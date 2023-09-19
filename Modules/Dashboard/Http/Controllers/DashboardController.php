<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;

class DashboardController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        return view('dashboard::pages.dashboard.index', compact('dataUserAuth'));
    }
}
