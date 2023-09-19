<?php

namespace Modules\Siswa\Http\Controllers;

use App\Services\UserService;
use Illuminate\Routing\Controller;
use Modules\Siswa\Entities\Siswa;
use Modules\Siswa\Services\SiswaService;

class SiswaController extends Controller
{
    protected $userService;

    protected $siswaService;

    public function __construct(UserService $userService, SiswaService $siswaService)
    {
        $this->userService = $userService;
        $this->siswaService = $siswaService;
    }

    public function class()
    {
        $dataUserAuth = $this->userService->getProfileUser();

        $allClasses = Siswa::orderBy('kelas', 'desc')->pluck('kelas');

        $classTotals = [];
        $previousClass = null;
        $total = 0;

        foreach ($allClasses as $class) {
            if ($previousClass === null) {
                $previousClass = $class;
                $total = 1;
            } elseif ($previousClass == $class) {
                $total++;
            } else {
                $classTotals[$previousClass] = ['key' => $previousClass, 'value' => $total];
                $previousClass = $class;
                $total = 1;
            }
        }

        if ($previousClass !== null) {
            $classTotals[$previousClass] = ['key' => $previousClass, 'value' => $total];
        }

        return view('siswa::pages.siswa.index', compact('dataUserAuth', 'classTotals'));
    }

    public function showClass($saveClassFromRoute)
    {
        $getDataSiswa = Siswa::where('kelas', $saveClassFromRoute)->latest()->get();
        $totalDataSiswa = $getDataSiswa->count();
        $dataUserAuth = $this->userService->getProfileUser();

        return view('siswa::pages.siswa.show_class', compact('getDataSiswa', 'dataUserAuth', 'totalDataSiswa', 'saveClassFromRoute'));
    }
}
