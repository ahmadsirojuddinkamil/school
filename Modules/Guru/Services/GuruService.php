<?php

namespace Modules\Guru\Services;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Modules\Absen\Entities\Absen;
use Modules\Guru\Entities\Guru;
use Ramsey\Uuid\Uuid;
use ZipArchive;

class GuruService
{
    public function createGuru($validateData)
    {
        $user = User::create([
            'uuid' => Uuid::uuid4(),
            'name' => $validateData['name'],
            'email' => $validateData['email'],
            'password' => $validateData['nuptk'],
        ]);
        $user->assignRole('guru');

        $saveFoto = $validateData['foto']->store('public/document_foto_resmi_guru');
        $changePublicToStoragePath = str_replace('public/', 'storage/', $saveFoto);

        $guru = Guru::create([
            'user_uuid' => $user->uuid,
            'mata_pelajaran_uuid' => null,

            'uuid' => Uuid::uuid4(),
            'name' => $validateData['name'],
            'nuptk' => $validateData['nuptk'],
            'nip' => $validateData['nip'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'agama' => $validateData['agama'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'status_perkawinan' => $validateData['status_perkawinan'],
            'jam_mengajar_awal' => $validateData['jam_mengajar_awal'],
            'jam_mengajar_akhir' => $validateData['jam_mengajar_akhir'],
            'pendidikan_terakhir' => $validateData['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $validateData['nama_tempat_pendidikan'],
            'ipk' => $validateData['ipk'],
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
            'nama_bank' => $validateData['nama_bank'],
            'nama_buku_rekening' => $validateData['nama_buku_rekening'],
            'no_rekening' => $validateData['no_rekening'],
        ]);

        Absen::create([
            'siswa_uuid' => null,
            'guru_uuid' => $guru->uuid,
            'uuid' => Uuid::uuid4()->toString(),
            'status' => 'guru',
            'keterangan' => 'hadir',
            'persetujuan' => 'setuju',
        ]);

        return true;
    }

    public function updateGuru($validateData, $saveUuidFromCall)
    {
        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with(['error' => 'Biodata guru gagal di edit!']);
        }

        if (isset($validateData['foto_new'])) {
            $changeFotoGuruPath = $validateData['foto_new']->store('public/document_biodata_guru');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoGuruPath);

            if ($biodataGuru->foto !== 'assets/dashboard/img/foto-guru.png') {
                File::delete($biodataGuru->foto);
            }
        }

        $biodataGuru->update([
            'name' => $validateData['name'],
            'nuptk' => $validateData['nuptk'],
            'nip' => $validateData['nip'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            'agama' => $validateData['agama'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'status_perkawinan' => $validateData['status_perkawinan'],
            'jam_mengajar_awal' => $validateData['jam_mengajar_awal'],
            'jam_mengajar_akhir' => $validateData['jam_mengajar_akhir'],
            'pendidikan_terakhir' => $validateData['pendidikan_terakhir'],
            'nama_tempat_pendidikan' => $validateData['nama_tempat_pendidikan'],
            'ipk' => $validateData['ipk'],
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
        ]);
    }

    public function updateTeachingHours($validateData, $saveUuidFromCall)
    {
        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with(['error' => 'Data guru tidak ditemukan!']);
        }

        $biodataGuru->update([
            'jam_mengajar_awal' => $validateData['jam_mengajar_awal'],
            'jam_mengajar_akhir' => $validateData['jam_mengajar_akhir'],
        ]);
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
}
