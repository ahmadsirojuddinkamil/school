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
            'nama_lengkap' => 'required|string',
            'email' => 'required|email',
            'nisn' => 'required|numeric',
            'asal_sekolah' => 'required|string',
            'alamat' => 'required|string',
            'telpon_siswa' => 'required|numeric',
            'jenis_kelamin' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|string',
            'tahun_daftar' => 'required|string',
            'tahun_lulus' => 'required|string',
            'jurusan' => 'required|string',
            'kelas' => 'required|string',
            'nama_ayah' => 'required|string',
            'nama_ibu' => 'required|string',
            'telpon_orang_tua' => 'required|numeric',
            'foto_new' => 'max:3100|file|mimetypes:image/jpeg,image/png',
            'foto_old' => 'string',
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
