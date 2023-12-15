<?php

namespace Modules\MataPelajaran\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\MataPelajaran\Entities\MataPelajaran;

class UpdateMataPelajaranRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */

    public function rules()
    {
        $currentUrl = request()->segments();
        $uuid = '';
        foreach ($currentUrl as $url) {
            if (preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $url)) {
                $uuid = $url;
            }
        }

        $mataPelajaran = MataPelajaran::where('uuid', $uuid)->first();

        if (!$mataPelajaran || !preg_match('/^[a-f\d]{8}-(?:[a-f\d]{4}-){3}[a-f\d]{12}$/i', $uuid)) {
            return [];
        }

        return [
            'jam_awal' => [
                'required',
                'string',
                Rule::unique('mata_pelajarans')->where(function ($query) use ($mataPelajaran) {
                    return $query->where('kelas', $mataPelajaran->kelas);
                })->ignore($mataPelajaran->id),
                Rule::unique('mata_pelajarans')->where(function ($query) use ($mataPelajaran) {
                    return $query->where('kelas', $mataPelajaran->kelas)
                        ->where(function ($query) {
                            $query->whereBetween('jam_awal', [request('jam_awal'), request('jam_akhir')])
                                ->orWhereBetween('jam_akhir', [request('jam_awal'), request('jam_akhir')]);
                        });
                })->ignore($mataPelajaran->id),
            ],
            'jam_akhir' => [
                'required',
                'string',
                Rule::unique('mata_pelajarans')->where(function ($query) use ($mataPelajaran) {
                    return $query->where('kelas', $mataPelajaran->kelas);
                })->ignore($mataPelajaran->id),
                Rule::unique('mata_pelajarans')->where(function ($query) use ($mataPelajaran) {
                    return $query->where('kelas', $mataPelajaran->kelas)
                        ->where(function ($query) {
                            $query->whereBetween('jam_awal', [request('jam_awal'), request('jam_akhir')])
                                ->orWhereBetween('jam_akhir', [request('jam_awal'), request('jam_akhir')]);
                        });
                })->ignore($mataPelajaran->id),
            ],
            'kelas' => 'required|string',
            'name_guru' => [
                'nullable',
                'string',
                Rule::unique('mata_pelajarans', 'name_guru')->ignore($mataPelajaran->id),
            ],
            'materi_pdf' => 'nullable|file|mimes:zip,rar|max:30000',
            'materi_ppt' => 'nullable|file|mimes:zip,rar|max:30000',
            'video' => 'nullable|string',
            'foto_new' => 'nullable|max:3100|file|mimetypes:image/jpeg,image/png',
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

    public function messages()
    {
        return [
            'jam_awal.unique' => 'Jam mengajar awal sudah terisi!',
            'jam_akhir.unique' => 'Jam mengajar akhir sudah terisi!',
        ];
    }
}
