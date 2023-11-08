<?php

namespace Modules\Ppdb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePpdbRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'email|required|unique:ppdbs',
            'nisn' => 'numeric|required',
            'name' => 'string|required',
            'asal_sekolah' => 'string|required',
            'alamat' => 'string|required',
            'telpon_siswa' => 'numeric|required',
            'jenis_kelamin' => 'string|required',
            'tempat_lahir' => 'string|required',
            'tanggal_lahir' => 'string|required',
            'tahun_daftar' => 'string|required',
            'nama_ayah' => 'string|required',
            'nama_ibu' => 'string|required',
            'telpon_orang_tua' => 'numeric|required',
            'bukti_pendaftaran_new' => 'file|mimetypes:image/jpeg,image/png|max:3100',
            'bukti_pendaftaran_old' => 'string',
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
