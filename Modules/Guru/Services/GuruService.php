<?php

namespace Modules\Guru\Services;

use Illuminate\Support\Facades\File;
use Modules\Guru\Entities\Guru;

class GuruService
{
    public function updateBiodataGuru($validateData, $saveUuidFromCall)
    {
        $biodataGuru = Guru::where('uuid', $saveUuidFromCall)->first();

        if (!$biodataGuru) {
            return redirect('/data-guru')->with(['error' => 'Biodata guru gagal di edit!']);
        }

        if (isset($validateData['foto_new'])) {
            $changeFotoGuruPath = $validateData['foto_new']->store('public/document_biodata_guru');
            $changePublicToStoragePath = str_replace('public/', 'storage/', $changeFotoGuruPath);

            if ($biodataGuru->foto !== 'assets/dashboard/img/foto-siswa.png') {
                File::delete($biodataGuru->foto);
            }
        }

        $biodataGuru->update([
            'name' => $validateData['name'],
            'nuptk' => $validateData['nuptk'],
            'nip' => $validateData['nip'],
            'tempat_lahir' => $validateData['tempat_lahir'],
            'tanggal_lahir' => $validateData['tanggal_lahir'],
            // 'mata_pelajaran' => $validateData['mata_pelajaran'],
            'agama' => $validateData['agama'],
            'jenis_kelamin' => $validateData['jenis_kelamin'],
            'status_perkawinan' => $validateData['status_perkawinan'],
            'jam_mengajar' => $validateData['jam_mengajar'],
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
            return redirect('/data-guru')->with(['error' => 'Jam mengajar guru gagal di edit!']);
        }

        $biodataGuru->update([
            'jam_mengajar' => $validateData['jam_mengajar'],
        ]);
    }
}
