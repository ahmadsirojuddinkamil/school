<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePpdbRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
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
            'jurusan' => 'string|required',
            'nama_ayah' => 'string|required',
            'nama_ibu' => 'string|required',
            'telpon_orang_tua' => 'numeric|required',
            'bukti_pendaftaran' => 'required|file|mimetypes:image/jpeg,image/png|max:3100',
        ];
    }
}
