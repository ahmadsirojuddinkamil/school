<?php

namespace Modules\Absen\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAbsenRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|string',
            'persetujuan' => 'required|string',
            'keterangan' => 'required|string',
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
