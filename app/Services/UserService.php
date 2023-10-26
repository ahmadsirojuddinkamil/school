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
            'super_admin' => 'super_admin',
            'admin' => 'admin',
            'tata_usaha' => 'tata_usaha',
            'satpam' => 'satpam',
            'pramukantor' => 'pramukantor',
            'kepala_sekolah' => 'kepala_sekolah',
            'guru' => 'guru',
            'orang_tua_siswa' => 'orang_tua_siswa',
            'siswa' => 'siswa',
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

    public function createRoleAndUserSuperAdmin()
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tata_usaha']);
        Role::create(['name' => 'satpam']);
        Role::create(['name' => 'pramukantor']);
        Role::create(['name' => 'kepala_sekolah']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'orang_tua_siswa']);
        Role::create(['name' => 'siswa']);

        $user = User::factory()->create();
        $user->assignRole('super_admin');

        return $user;
    }

    public function createRoleAndUserAdmin()
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tata_usaha']);
        Role::create(['name' => 'satpam']);
        Role::create(['name' => 'pramukantor']);
        Role::create(['name' => 'kepala_sekolah']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'orang_tua_siswa']);
        Role::create(['name' => 'siswa']);

        $user = User::factory()->create();
        $user->assignRole('admin');

        return $user;
    }

    public function createRoleAndUserSiswa()
    {
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tata_usaha']);
        Role::create(['name' => 'satpam']);
        Role::create(['name' => 'pramukantor']);
        Role::create(['name' => 'kepala_sekolah']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'orang_tua_siswa']);
        Role::create(['name' => 'siswa']);

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
        Role::create(['name' => 'super_admin']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'tata_usaha']);
        Role::create(['name' => 'satpam']);
        Role::create(['name' => 'pramukantor']);
        Role::create(['name' => 'kepala_sekolah']);
        Role::create(['name' => 'guru']);
        Role::create(['name' => 'orang_tua_siswa']);
        Role::create(['name' => 'siswa']);

        $user = User::factory()->create();
        $user->assignRole('guru');

        return $user;
    }
}
