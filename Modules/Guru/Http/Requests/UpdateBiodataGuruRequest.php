<?php

namespace Modules\Guru\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBiodataGuruRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:50',
            'nuptk' => 'required|string|max:16',
            'nip' => 'required|string|max:18',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|string',
            // 'mata_pelajaran' => 'required|string',
            'agama' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'status_perkawinan' => 'required|string',
            'jam_mengajar' => 'required|string',
            'pendidikan_terakhir' => 'required|string',
            'nama_tempat_pendidikan' => 'required|string',
            'ipk' => 'required|string',
            'tahun_lulus' => 'required|string',
            'alamat_rumah' => 'required|string',
            'provinsi' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'kode_pos' => 'required|string',
            'email' => 'required|string|email',
            'no_telpon' => 'required|string',
            'tahun_daftar' => 'required|string',
            'tahun_keluar' => 'required|string',
            'foto_new' => 'max:3100|file|mimetypes:image/jpeg,image/png',
            'foto_old' => 'string',
            'nama_bank' => 'required|string',
            'nama_buku_rekening' => 'required|string',
            'no_rekening' => 'required|string',
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
