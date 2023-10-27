<?php

namespace Modules\Ppdb\Services;

use App\Models\{User};
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\File;
use Modules\Absen\Entities\Absen;
use Modules\Ppdb\Entities\{OpenPpdb, Ppdb};
use Modules\Ppdb\Jobs\SendEmailPpdbJob;
use Modules\Siswa\Entities\Siswa;
use Ramsey\Uuid\Uuid;
use ZipArchive;

class PpdbService
{
    public function getEditTime()
    {
        return [
            'today' => (new DateTime())->format('Y-m-d'),
            'todayDate' => (new DateTime())->format('Y-m-d'),
            'minDate' => (new DateTime('-21 years'))->format('Y-m-d'),
        ];
    }

    public function openPpdb($validateData)
    {
        OpenPpdb::create([
            'uuid' => Uuid::uuid4()->toString(),
            'tanggal_mulai' => $validateData['tanggal_mulai'],
            'tanggal_akhir' => $validateData['tanggal_akhir'],
        ]);
    }

    public function createPpdb($validateData)
    {
        $buktiPendaftaranPpdbPath = $validateData['bukti_pendaftaran']->store('public/document_bukti_pendaftaran_siswa_baru');

        $changePublicToStoragePath = str_replace('public/', 'storage/', $buktiPendaftaranPpdbPath);

        Ppdb::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'nisn' => $validateData['nisn'],
            'asal_sekolah' => $validateData['asal_sekolah'],
            'alamat' => $validateData['alamat'],
            'telpon_siswa' => $validateData['telpon_siswa'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
            'tahun_daftar' => Carbon::now()->format('Y'),
            'bukti_pendaftaran' => $changePublicToStoragePath,
        ]);
    }

    public function listYearPpdb($saveYearFromController)
    {
        $yearTotals = [];
        $previousYear = null;
        $total = 0;

        foreach ($saveYearFromController as $year) {
            if ($previousYear === null) {
                $previousYear = $year;
                $total = 1;
            } elseif ($previousYear == $year) {
                $total++;
            } else {
                $yearTotals[$previousYear] = ['key' => $previousYear, 'value' => $total];
                $previousYear = $year;
                $total = 1;
            }
        }

        if ($previousYear !== null) {
            $yearTotals[$previousYear] = ['key' => $previousYear, 'value' => $total];
        }

        return $yearTotals;
    }

    public function openPpdbTime()
    {
        $todayDate = (new DateTime())->format('Y-m-d');
        $maxDate = date_modify((new DateTime()), '+7 days')->format('Y-m-d');

        return [
            'todayDate' => $todayDate,
            'maxDate' => $maxDate,
        ];
    }

    public function acceptPpdb($saveUuidFromController)
    {
        $dataPpdb = Ppdb::where('uuid', $saveUuidFromController)->lockForUpdate()->first();

        $siswa = Siswa::create([
            'user_id' => null,
            'mata_pelajaran_id' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $dataPpdb->name,
            'nisn' => $dataPpdb->nisn,
            'kelas'  => 10,
            'tempat_lahir' => $dataPpdb->tempat_lahir,
            'tanggal_lahir' => $dataPpdb->tanggal_lahir,
            'agama' => null,
            'jenis_kelamin' => $dataPpdb->jenis_kelamin,
            'asal_sekolah' => $dataPpdb->asal_sekolah,
            'nem' => null,
            'tahun_lulus' => null,
            'alamat_rumah' => null,
            'provinsi' => null,
            'kecamatan' => null,
            'kelurahan' => null,
            'kode_pos' => null,
            'email' => $dataPpdb->email,
            'no_telpon' => $dataPpdb->telpon_siswa,
            'tahun_daftar' => $dataPpdb->tahun_daftar,
            'tahun_keluar' => null,
            'foto' => 'assets/dashboard/img/foto-siswa.png',
            'nama_bank' => null,
            'nama_buku_rekening' => null,
            'no_rekening' => null,
            'nama_ayah' => $dataPpdb->nama_ayah,
            'nama_ibu' => $dataPpdb->nama_ayah,
            'nama_wali' => null,
            'telpon_orang_tua' => $dataPpdb->telpon_orang_tua,
        ]);

        $user = User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $dataPpdb->name,
            'email' => $dataPpdb->email,
            'password' => $dataPpdb->nisn,
        ]);
        $user->assignRole('siswa');

        $siswa->update([
            'user_id' => $user->id,
        ]);

        Absen::create([
            'siswa_id' => $siswa->id,
            'guru_id' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'status' => $siswa->kelas,
            'keterangan' => 'hadir',
            'persetujuan' => 'setuju',
        ]);

        // send email
        $dataEmail = [
            'email' => $dataPpdb->email,
            'nama' => $dataPpdb->name,
        ];
        dispatch(new SendEmailPpdbJob($dataEmail));
    }

    public function editPpdb($validateData, $saveUuidFromController)
    {
        $getPpdb = Ppdb::where('uuid', $saveUuidFromController)->first();

        if (isset($validateData['bukti_pendaftaran_new'])) {
            $changeBuktiPendaftaranPpdbPath = $validateData['bukti_pendaftaran_new']->store('public/document_bukti_pendaftaran_siswa_baru');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeBuktiPendaftaranPpdbPath);
            File::delete($getPpdb->bukti_pendaftaran);
        }

        $getPpdb->update([
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'nisn' => $validateData['nisn'],
            'asal_sekolah' => $validateData['asal_sekolah'],
            'alamat' => $validateData['alamat'],
            'telpon_siswa' => $validateData['telpon_siswa'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'tahun_daftar' => $validateData['tahun_daftar'],
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
            'bukti_pendaftaran' => $changePublicToStoragePath ?? $validateData['bukti_pendaftaran_old'],
        ]);
    }

    public function createZip($saveFileNameFromCall, $saveFolderPathFromCall)
    {
        $zip = new ZipArchive;
        $zipFileName = $saveFileNameFromCall;
        $folderPath = $saveFolderPathFromCall;

        $zip->open($zipFileName, ZipArchive::CREATE);

        $files = glob($folderPath . '/*');
        foreach ($files as $file) {
            $fileName = basename($file);
            $zip->addFile($file, $fileName);
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
}
