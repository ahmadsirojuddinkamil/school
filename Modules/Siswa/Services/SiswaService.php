<?php

namespace Modules\Siswa\Services;

use DateTime;
use Illuminate\Support\Facades\File;
use Modules\Siswa\Entities\Siswa;

class SiswaService
{
    public function getStatusSiswaActiveOrNot()
    {
        $getAllSiswa = Siswa::latest()->pluck('tahun_lulus');

        $statusSiswa = [
            'belum_lulus' => 0,
            'sudah_lulus' => 0,
        ];

        foreach ($getAllSiswa as $tahunLulus) {
            if ($tahunLulus === null) {
                $statusSiswa['belum_lulus']++;
            } else {
                $statusSiswa['sudah_lulus']++;
            }
        }

        return $statusSiswa;
    }

    public function getListSiswaClass()
    {
        $getAllSiswa = Siswa::latest()->pluck('kelas');

        $listClass = [
            '10' => 0,
            '11' => 0,
            '12' => 0,
        ];

        foreach ($getAllSiswa as $class) {
            if (in_array($class, ['10', '11', '12'])) {
                $listClass[$class]++;
            }
        }

        if (array_sum($listClass) === 0) {
            return [];
        }

        return $listClass;
    }

    public function updateDataSiswa($validateData, $saveUuidFromCaller)
    {
        $getDataSiswa = Siswa::where('uuid', $saveUuidFromCaller)->first();

        if (! $getDataSiswa) {
            return abort(404);
        }

        if (isset($validateData['foto_new'])) {
            $changeFotoSiswaPath = $validateData['foto_new']->store('public/document_foto_resmi_siswa');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoSiswaPath);

            if ($getDataSiswa->foto !== 'assets/dashboard/img/foto-siswa.png') {
                File::delete($getDataSiswa->foto);
            }
        }

        $getDataSiswa->update([
            'nama_lengkap' => $validateData['nama_lengkap'],
            'email' => $validateData['email'],
            'nisn' => $validateData['nisn'],
            'kelas' => $validateData['kelas'] ?? null,
            'asal_sekolah' => $validateData['asal_sekolah'],
            'alamat' => $validateData['alamat'],
            'telpon_siswa' => $validateData['telpon_siswa'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'tahun_daftar' => $validateData['tahun_daftar'],
            'tahun_lulus' => $validateData['tahun_lulus'],
            'jurusan' => $validateData['jurusan'],
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
            'foto' => $changePublicToStoragePath ?? $validateData['foto_old'],
        ]);
    }

    public function checkGraduatedUuidOrNot($saveUuidFromCaller)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return redirect()->route('siswa.graduated')->with(['error' => 'Data siswa tidak ditemukan!']);
        }
    }

    public function checkEditUuidOrNot($saveUuidFromCaller)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return redirect()->route('siswa.status')->with(['error' => 'Data siswa tidak ditemukan!']);
        }
    }

    public function getEditTime()
    {
        return [
            'today' => (new DateTime())->format('Y-m-d'),
            'todayDate' => (new DateTime())->format('Y-m-d'),
            'minDate' => (new DateTime('-21 years'))->format('Y-m-d'),
        ];
    }

    public function checkUpdateUuidOrNot($saveUuidFromCaller)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return abort(404);
        }
    }

    public function checkValidYear($saveYearFromCaller)
    {
        if (! preg_match('/^\d{4}$/', $saveYearFromCaller)) {
            return abort(404);
        }
    }

    public function checkDeleteUuidOrNot($saveUuidFromCaller)
    {
        if (! preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $saveUuidFromCaller)) {
            return abort(404);
        }
    }
}
