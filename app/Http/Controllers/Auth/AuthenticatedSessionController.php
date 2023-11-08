<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        // $findUser = User::find(Auth::id());
        // $roles = [
        //     'super_admin' => 'super_admin',
        //     'admin' => 'admin',
        //     'tata_usaha' => 'tata_usaha',
        //     'satpam' => 'satpam',
        //     'pramukantor' => 'pramukantor',
        //     'kepala_sekolah' => 'kepala_sekolah',
        //     'guru' => 'guru',
        //     'orang_tua_siswa' => 'orang_tua_siswa',
        //     'siswa' => 'siswa',
        // ];

        // $userRole = null;

        // foreach ($roles as $roleName) {
        //     if ($findUser->hasRole($roleName)) {
        //         $userRole = $roleName;
        //         break;
        //     }
        // }

        // session(['user_data' => [$findUser, $userRole]]);

        $request->session()->regenerate();

        return redirect()->intended(RouteServiceProvider::HOME);
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
