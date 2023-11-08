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
            'nisn' => 'required|numeric|unique:siswas',
            'kelas' => 'nullable|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|string',
            'agama' => 'nullable|string',
            'jenis_kelamin' => 'required|string',
            'asal_sekolah' => 'required|string',
            'nem' => 'nullable|string',
            'tahun_lulus' => 'nullable|string',
            'alamat_rumah' => 'nullable|string',
            'provinsi' => 'nullable|string',
            'kecamatan' => 'nullable|string',
            'kelurahan' => 'nullable|string',
            'kode_pos' => 'nullable|numeric',
            'email' => 'required|email|unique:siswas',
            'no_telpon' => 'required|numeric',
            'tahun_daftar' => 'required|string',
            'tahun_keluar' => 'nullable|string',
            'foto_new' => 'max:3100|file|mimetypes:image/jpeg,image/png',
            'foto_old' => 'string',
            'nama_bank' => 'nullable|string',
            'nama_buku_rekening' => 'nullable|string',
            'no_rekening' => 'nullable|string',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'nama_wali' => 'nullable|string',
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
