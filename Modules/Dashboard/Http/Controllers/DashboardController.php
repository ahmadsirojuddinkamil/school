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
        $dataUserAuth = $this->userService->getProfileUser();

        // $id = Auth::id();
        // $user = User::with('guru', 'siswa')->find($id);
        // $user = User::with('guru.absens', 'siswa.absens')->find($id);
        // dd($user);

        // dd($user->siswa->with('absens')->latest()->get());
        // dd($user->siswa->user()->latest()->get());
        // dd($user->siswa->absens()->latest()->get());

        // $userProfile = Session::get('user_data');
        // dd($userProfile);

        return view('dashboard::layouts.index', compact('dataUserAuth'));
    }
}
