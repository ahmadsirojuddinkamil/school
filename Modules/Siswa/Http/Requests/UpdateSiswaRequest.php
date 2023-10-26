<?php

namespace Modules\Siswa\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSiswaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string',
            'nisn' => 'required|numeric',
            'kelas' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|string',
            'agama' => 'required|string',
            'jenis_kelamin' => 'required|string',
            'asal_sekolah' => 'required|string',
            'nem' => 'required|string',
            'tahun_lulus' => 'required|string',
            'alamat_rumah' => 'required|string',
            'provinsi' => 'required|string',
            'kecamatan' => 'required|string',
            'kelurahan' => 'required|string',
            'kode_pos' => 'required|numeric',
            'email' => 'required|email',
            'no_telpon' => 'required|numeric',
            'tahun_daftar' => 'required|string',
            'tahun_keluar' => 'required|string',
            'foto_new' => 'max:3100|file|mimetypes:image/jpeg,image/png',
            'foto_old' => 'string',
            'nama_bank' => 'required|string',
            'nama_buku_rekening' => 'required|string',
            'no_rekening' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'nama_wali' => 'required|string',
            'telpon_orang_tua' => 'required|numeric',
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
