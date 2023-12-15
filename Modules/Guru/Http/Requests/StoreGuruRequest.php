<?php

namespace Modules\Guru\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGuruRequest extends FormRequest
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
            'nuptk' => 'required|string|max:16|unique:gurus',
            'nip' => 'nullable|string|max:18|unique:gurus',
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
            'email' => 'required|string|email|unique:gurus',
            'no_telpon' => 'required|string',
            'tahun_daftar' => 'required|string',
            'tahun_keluar' => 'nullable|string',
            'foto' => 'max:3100|file|mimetypes:image/jpeg,image/png',
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
