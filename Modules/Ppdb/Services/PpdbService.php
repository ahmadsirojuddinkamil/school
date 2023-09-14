<?php

namespace Modules\Ppdb\Services;

use App\Models\{User};
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Modules\Ppdb\Entities\{Ppdb};
use Modules\Siswa\Entities\{Siswa};
use Ramsey\Uuid\Uuid;

class PpdbService
{
    public function saveDataSiswaPpdb($validateData)
    {
        $buktiPendaftaranPpdbPath = $validateData['bukti_pendaftaran']->store('public/document_bukti_pendaftaran_siswa_baru');

        $changePublicToStoragePath = str_replace('public/', 'storage/', $buktiPendaftaranPpdbPath);

        Ppdb::create([
            'uuid' => Uuid::uuid4()->toString(),
            'nama_lengkap' => $validateData['nama_lengkap'],
            'email' => $validateData['email'],
            'nisn' => $validateData['nisn'],
            'asal_sekolah' => $validateData['asal_sekolah'],
            'alamat' => $validateData['alamat'],
            'telpon_siswa' => $validateData['telpon_siswa'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'jurusan' => $validateData['jurusan'],
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
            'tahun_daftar' => Carbon::now()->format('Y'),
            'bukti_pendaftaran' => $changePublicToStoragePath,
        ]);
    }

    public function acceptPpdb($saveUuidFromController)
    {
        $getPpdb = Ppdb::where('uuid', $saveUuidFromController)->lockForUpdate()->first();

        $siswa = Siswa::create([
            'uuid' => Uuid::uuid4()->toString(),
            'user_id' => null,

            'nama_lengkap' => $getPpdb->nama_lengkap,
            'email' => $getPpdb->email,
            'nisn' => $getPpdb->nisn,
            'asal_sekolah' => $getPpdb->asal_sekolah,
            'kelas' => 1,
            'alamat' => $getPpdb->alamat,
            'telpon_siswa' => $getPpdb->telpon_siswa,
            'jenis_kelamin' => $getPpdb->jenis_kelamin,
            'tempat_lahir' => $getPpdb->tempat_lahir,
            'tanggal_lahir' => $getPpdb->tanggal_lahir,
            'tahun_daftar' => $getPpdb->tahun_daftar,
            'jurusan' => $getPpdb->jurusan,
            'nama_ayah' => $getPpdb->nama_ayah,
            'nama_ibu' => $getPpdb->nama_ibu,
            'telpon_orang_tua' => $getPpdb->telpon_orang_tua,
            'foto' => null,
        ]);

        $user = User::create([
            'uuid' => Uuid::uuid4()->toString(),
            'name' => $getPpdb->nama_lengkap,
            'email' => $getPpdb->email,
            'password' => $getPpdb->nisn,
        ]);
        $user->assignRole('siswa');

        $siswa->update([
            'user_id' => $user->id,
        ]);
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
            'nama_lengkap' => $validateData['nama_lengkap'],
            'email' => $validateData['email'],
            'nisn' => $validateData['nisn'],
            'asal_sekolah' => $validateData['asal_sekolah'],
            'alamat' => $validateData['alamat'],
            'telpon_siswa' => $validateData['telpon_siswa'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'tahun_daftar' => $validateData['tahun_daftar'],
            'jurusan' => $validateData['jurusan'],
            'nama_ayah' => $validateData['nama_ayah'],
            'nama_ibu' => $validateData['nama_ibu'],
            'telpon_orang_tua' => $validateData['telpon_orang_tua'],
            'bukti_pendaftaran' => $changePublicToStoragePath ?? $validateData['bukti_pendaftaran_old'],
        ]);
    }
}
