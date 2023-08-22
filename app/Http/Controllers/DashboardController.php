<?php

namespace App\Http\Controllers;

use App\Services\UserService;

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

        return view('pages.dashboard.index', compact('dataUserAuth'));
    }
}
