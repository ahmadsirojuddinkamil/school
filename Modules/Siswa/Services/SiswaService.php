<?php

namespace Modules\Siswa\Services;

use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\File;
use Modules\Absen\Entities\Absen;
use Modules\Siswa\Entities\Siswa;
use Ramsey\Uuid\Uuid;
use ZipArchive;

class SiswaService
{
    public function statusSiswaActiveOrNot()
    {
        $getAllSiswa = Siswa::latest()->pluck('tahun_keluar');

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

    public function listSiswaInClass()
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

    public function updateDataSiswa($validateData, $saveUuidFromCall)
    {
        $getDataSiswa = Siswa::where('uuid', $saveUuidFromCall)->first();

        if (!$getDataSiswa) {
            return redirect('/data-siswa/' . $saveUuidFromCall . '/edit')->with('error', 'Data siswa tidak ditemukan!');
        }

        if (isset($validateData['foto_new'])) {
            $changeFotoSiswaPath = $validateData['foto_new']->store('public/document_foto_resmi_siswa');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoSiswaPath);

            if ($getDataSiswa->foto !== 'assets/dashboard/img/foto-siswa.png') {
                File::delete($getDataSiswa->foto);
            }
        }

        $getDataSiswa->update([
            'name' => $validateData['name'],
            'nisn' => $validateData['nisn'],
            'kelas' => $validateData['kelas'] ?? null,
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'agama' => $validateData['agama'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'asal_sekolah' => $validateData['asal_sekolah'],
            'nem' => $validateData['nem'],
            'tahun_lulus' => $validateData['tahun_lulus'],
            'alamat_rumah' => $validateData['alamat_rumah'],
            'provinsi' => $validateData['provinsi'],
            'kecamatan' => $validateData['kecamatan'],
            'kelurahan' => $validateData['kelurahan'],
            'kode_pos' => $validateData['kode_pos'],
            'email' => $validateData['email'],
            'no_telpon' => $validateData['no_telpon'],
            'tahun_daftar' => $validateData['tahun_daftar'],
            'tahun_keluar' => $validateData['tahun_keluar'],
            'foto' => $changePublicToStoragePath ?? $validateData['foto_old'],
            'nama_bank' => $validateData['nama_bank'],
            'nama_buku_rekening' => $validateData['nama_buku_rekening'],
            'no_rekening' => $validateData['no_rekening'],
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'nama_wali' => $validateData['nama_wali'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
        ]);
    }

    public function getEditTime()
    {
        return [
            'today' => (new DateTime())->format('Y-m-d'),
            'todayDate' => (new DateTime())->format('Y-m-d'),
            'minDate' => (new DateTime('-21 years'))->format('Y-m-d'),
        ];
    }

    public function createZip($saveFileNameFromCall, $saveFolderPathFromCall)
    {
        $zip = new ZipArchive;
        $zipFileName = $saveFileNameFromCall;
        $folderPath = $saveFolderPathFromCall;

        $zip->open($zipFileName, ZipArchive::CREATE);
        $zip->addEmptyDir($saveFileNameFromCall);

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $relativePath = $saveFileNameFromCall . '/' . $fileName;
            $zip->addFile($file, $relativePath);
        }

        $zip->close();

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }

        return true;
    }

    public function createSiswa($validateData)
    {
        $saveFoto = $validateData['foto']->store('public/document_foto_resmi_siswa');
        $changePublicToStoragePath = str_replace('public/', 'storage/', $saveFoto);

        $siswa = Siswa::create([
            'user_uuid' => null,
            'mata_pelajaran_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'nisn' => $validateData['nisn'],
            'kelas' => $validateData['kelas'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'agama' => $validateData['agama'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'asal_sekolah' => $validateData['asal_sekolah'],
            'nem' => $validateData['nem'],
            'tahun_lulus' => $validateData['tahun_lulus'],
            'alamat_rumah' => $validateData['alamat_rumah'],
            'provinsi' => $validateData['provinsi'],
            'kecamatan' => $validateData['kecamatan'],
            'kelurahan' => $validateData['kelurahan'],
            'kode_pos' => $validateData['kode_pos'],
            'email' => $validateData['email'],
            'no_telpon' => $validateData['no_telpon'],
            'tahun_daftar' => $validateData['tahun_daftar'],
            'tahun_keluar' => $validateData['tahun_keluar'],
            'foto' => $changePublicToStoragePath,
            'nama_bank' => null,
            'nama_buku_rekening' => null,
            'no_rekening' => null,
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'nama_wali' => $validateData['nama_wali'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
        ]);

        $user = User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $siswa->name,
            'email' => $siswa->email,
            'password' => $siswa->nisn,
        ]);
        $user->assignRole('siswa');

        $siswa->update([
            'user_uuid' => $user->uuid,
        ]);

        Absen::create([
            'siswa_uuid' => $siswa->uuid,
            'guru_uuid' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'status' => $siswa->kelas,
            'keterangan' => 'hadir',
            'persetujuan' => 'setuju',
        ]);

        return true;
    }
}
