<?php

namespace App\Services;

use App\Models\{Payment, Siswa};
use Ramsey\Uuid\Uuid;

class CreatePpdbService
{
    public function SaveDataSiswaPpdb($ValidateData)
    {
        $BuktiPendaftaranSiswaPath = $ValidateData['bukti_pendaftaran_siswa_baru']->store('public/document_bukti_pendaftaran_siswa_baru');

        $siswa = Siswa::create([
            'uuid_payment' => null,
            'uuid' => Uuid::uuid4()->toString(),
            'nama_lengkap' => $ValidateData['nama_lengkap'],
            'email' => $ValidateData['email'],
            'password' => $ValidateData['nisn'],
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
        ]);

        $ChangePublicToStoragePath = str_replace('public/', 'storage/', $BuktiPendaftaranSiswaPath);
        $payment = Payment::create([
            'uuid_siswa' => $siswa->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'bukti_pendaftaran_siswa_baru' => $ChangePublicToStoragePath,
        ]);

        $siswa->update([
            'uuid_payment' => $payment->uuid,
        ]);
    }
}
