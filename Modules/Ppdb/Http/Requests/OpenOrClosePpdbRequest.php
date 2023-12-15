<?php

namespace Modules\Ppdb\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenOrClosePpdbRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'tanggal_mulai' => 'string|date|required',
            'tanggal_akhir' => 'string|date|required',
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
