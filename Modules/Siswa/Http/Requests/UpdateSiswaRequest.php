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
            'email' => 'email|required',
            'nisn' => 'numeric|required',
            'nama_lengkap' => 'string|required',
            'asal_sekolah' => 'string|required',
            'alamat' => 'string|required',
            'telpon_siswa' => 'numeric|required',
            'jenis_kelamin' => 'string|required',
            'tempat_lahir' => 'string|required',
            'tanggal_lahir' => 'string|required',
            'tahun_daftar' => 'string|required',
            'jurusan' => 'string|required',
            'nama_ayah' => 'string|required',
            'nama_ibu' => 'string|required',
            'telpon_orang_tua' => 'numeric|required',
            'foto' => 'string|required',
            // 'foto' => 'required|file|mimetypes:image/jpeg,image/png|max:3100',
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
