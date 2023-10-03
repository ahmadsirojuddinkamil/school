<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

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

            $roleToLoad = null;

            foreach ($roles as $role => $value) {
                if ($findUser->hasRole($role)) {
                    $roleToLoad = $role;
                    break;
                }
            }

            if ($roleToLoad) {
                $findUser->load($roleToLoad);
            }

            return [$findUser, $roleToLoad];
        }

        return null;
    }

    public function createRoleAndUserAdmin()
    {
        Role::create(['name' => 'siswa']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kepala_sekolah']);

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function createRoleAndUserSiswa()
    {
        Role::create(['name' => 'siswa']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kepala_sekolah']);

        $user = User::factory()->create();
        $user->assignRole('siswa');

        return $user;
    }
}
