<?php

namespace App\Services;

use App\Models\{Ppdb, Siswa, User};
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class PpdbService
{
    public function saveDataSiswaPpdb($ValidateData)
    {
        $BuktiPendaftaranPpdbPath = $ValidateData['bukti_pendaftaran']->store('public/document_bukti_pendaftaran_siswa_baru');

        $ChangePublicToStoragePath = str_replace('public/', 'storage/', $BuktiPendaftaranPpdbPath);

        Ppdb::create([
            'uuid' => Uuid::uuid4()->toString(),
            'nama_lengkap' => $ValidateData['nama_lengkap'],
            'email' => $ValidateData['email'],
            'nisn' => $ValidateData['nisn'],
            'asal_sekolah' => $ValidateData['asal_sekolah'],
            'alamat' => $ValidateData['alamat'],
            'telpon_siswa' => $ValidateData['telpon_siswa'],
            'jenis_kelamin' => $ValidateData['jenis_kelamin'],
            'tempat_lahir' => $ValidateData['tempat_lahir'],
            'tanggal_lahir' => $ValidateData['tanggal_lahir'],
            'jurusan' => $ValidateData['jurusan'],
            'nama_ayah' => $ValidateData['nama_ayah'],
            'nama_ibu' => $ValidateData['nama_ibu'],
            'telpon_orang_tua' => $ValidateData['telpon_orang_tua'],
            'tahun_daftar' => Carbon::now()->format('d, m Y'),
            'bukti_pendaftaran' => $ChangePublicToStoragePath,
        ]);
    }

    public function acceptPpdb($saveUuidFromController)
    {
        $getPpdb = Ppdb::where('uuid', $saveUuidFromController)->first();

        Siswa::create([
            'uuid_payment' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'nama_lengkap' => $getPpdb->nama_lengkap,
            'email' => $getPpdb->email,
            'nisn' => $getPpdb->nisn,
            'asal_sekolah' => $getPpdb->asal_sekolah,
            'alamat' => $getPpdb->alamat,
            'telpon_siswa' => $getPpdb->telpon_siswa,
            'jenis_kelamin' => $getPpdb->jenis_kelamin,
            'tempat_lahir' => $getPpdb->tempat_lahir,
            'tanggal_lahir' => $getPpdb->tanggal_lahir,
            'jurusan' => $getPpdb->jurusan,
            'nama_ayah' => $getPpdb->nama_ayah,
            'nama_ibu' => $getPpdb->nama_ibu,
            'telpon_orang_tua' => $getPpdb->telpon_orang_tua,
        ]);

        $user = User::create([
            'name' => $getPpdb->nama_lengkap,
            'email' => $getPpdb->email,
            'password' => $getPpdb->nisn,
        ]);

        $user->assignRole('siswa');
    }

    public function editPpdb($ValidateData, $saveUuidFromController)
    {
        $getPpdb = Ppdb::where('uuid', $saveUuidFromController)->first();

        $getPpdb->update([
            'nama_lengkap' => $ValidateData['nama_lengkap'],
            'email' => $ValidateData['email'],
            'nisn' => $ValidateData['nisn'],
            'asal_sekolah' => $ValidateData['asal_sekolah'],
            'alamat' => $ValidateData['alamat'],
            'telpon_siswa' => $ValidateData['telpon_siswa'],
            'jenis_kelamin' => $ValidateData['jenis_kelamin'],
            'tempat_lahir' => $ValidateData['tempat_lahir'],
            'tanggal_lahir' => $ValidateData['tanggal_lahir'],
            'jurusan' => $ValidateData['jurusan'],
            'nama_ayah' => $ValidateData['nama_ayah'],
            'nama_ibu' => $ValidateData['nama_ibu'],
            'telpon_orang_tua' => $ValidateData['telpon_orang_tua'],
            'bukti_pendaftaran' => $ValidateData['bukti_pendaftaran'],
        ]);
    }
}
