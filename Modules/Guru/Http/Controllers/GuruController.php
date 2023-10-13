<?php

namespace Modules\Guru\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;

class GuruController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function listCourse()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        return view('guru::layouts.list_teacher', compact('dataUserAuth'));
    }
}
