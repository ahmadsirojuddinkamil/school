<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getProfileUser()
    {
        $findUser = User::find(Auth::id());

        if ($findUser) {
            $roles = [
                'siswa' => 'siswa',
                'guru' => 'guru',
                'admin' => 'admin',
                'kepala_sekolah' => 'kepala_sekolah',
            ];

            foreach ($roles as $role => $value) {
                if ($findUser->hasRole($role)) {
                    return [$findUser, $value];
                }
            }
        }

        return null;
    }
}
