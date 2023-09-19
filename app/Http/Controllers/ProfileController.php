<?php

namespace App\Http\Controllers;

use App\Http\Requests\FotoProfileUpdateRequest;
use App\Http\Requests\ProfileUpdateRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $dataUserAuth = $this->userService->getProfileUser();

        return view('profile.edit', [
            'user' => $request->user(),
            'dataUserAuth' => $dataUserAuth,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateFotoProfile(FotoProfileUpdateRequest $request): RedirectResponse
    {
        $validateData = $request->validated();

        $saveImageToStorage = $validateData['foto_profile']->store('public/document_foto_profile_user');
        $changePublicToStoragePath = str_replace('public/', 'storage/', $saveImageToStorage);

        $user = $request->user();
        if ($user->foto_profile) {
            File::delete($user->foto_profile);
        }

        $user->update([
            'foto_profile' => $changePublicToStoragePath,
        ]);

        // return Redirect::route('profile.edit')->with('status', 'foto profile berhasil di update!');
        return redirect()->route('profile.edit')->with(['status' => 'foto profile berhasil di update!']);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
