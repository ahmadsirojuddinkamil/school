<?php

namespace Modules\Ppdb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Ppdb\Entities\Ppdb;

class UpdatePpdbRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        $uuid = last(request()->segments());
        $ppdb = preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $uuid) ? Ppdb::where('uuid', $uuid)->first() : Ppdb::first();

        return [
            'email' => 'required|email|unique:ppdbs,email,' . $ppdb->id,
            'nisn' => 'required|numeric|unique:ppdbs,nisn,' . $ppdb->id,
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
