<?php

namespace Modules\Absen\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeleteDateAbsenSiswaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tanggal' => 'required|string',
            'uuid' => 'required|string|uuid',
            'kelas' => 'required|integer',
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