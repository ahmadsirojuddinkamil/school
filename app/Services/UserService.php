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

        $roles = [
            'admin' => 'admin',
            'guru' => 'guru',
            'siswa' => 'siswa',
            'kepala_sekolah' => 'kepala_sekolah',
        ];

        $userRole = null;

        foreach ($roles as $roleName) {
            if ($findUser->hasRole($roleName)) {
                $userRole = $roleName;
                break;
            }
        }

        return [$findUser, $userRole];
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

    public function createUserSiswa()
    {
        $user = User::factory()->create();
        $user->assignRole('siswa');

        return $user;
    }

    public function createRoleAndUserGuru()
    {
        Role::create(['name' => 'siswa']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'kepala_sekolah']);

        $user = User::factory()->create();
        $user->assignRole('guru');

        return $user;
    }
}
