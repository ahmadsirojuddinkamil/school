<?php

namespace Modules\Guru\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Session;
use Modules\Guru\Entities\Guru;

class UpdateBiodataGuruRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $dataUserAuth = Session::get('userData');

        // untuk guru
        if ($dataUserAuth[1] == 'guru') {
            $guru = $dataUserAuth[0]->load('guru')->guru;
        }

        // untuk admin
        $currentUrl = request()->segments();
        $uuid = '';
        foreach ($currentUrl as $url) {
            if (preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $url)) {
                $uuid = $url;
            }
        }

        if (preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $uuid)) {
            $guru = Guru::where('uuid', $uuid)->first();
        } else {
            $latestGuru = Guru::first();
            $guru = Guru::where('uuid', $latestGuru->uuid)->first();
        }

        // kalau bukan uuid
        if (!$guru) {
            return [];
        }

        return [
            'name' => 'required|string|max:50',
            'nuptk' => 'required|string|max:16|unique:gurus,nuptk,' . $guru->id,
            'nip' => 'nullable|string|max:18|unique:gurus,nip,' . $guru->id,
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|string',
            'agama' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'status_perkawinan' => 'required|string',
            'jam_mengajar_awal' => 'required|string',
            'jam_mengajar_akhir' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'nama_tempat_pendidikan' => 'required|string',
            'ipk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'alamat_rumah' => 'required|string',
            'provinsi' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'kode_pos' => 'required|string',
            'email' => 'required|email|unique:gurus,email,' . $guru->id,
            'no_telpon' => 'required|string',
            'tahun_daftar' => 'required|string',
            'tahun_keluar' => 'nullable|string',
            'foto_new' => 'max:3100|file|mimetypes:image/jpeg,image/png',
            'foto_old' => 'string',
            'nama_bank' => 'nullable|string',
            'nama_buku_rekening' => 'nullable|string',
            'no_rekening' => 'nullable|string',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
