<?php

namespace Modules\MataPelajaran\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Modules\MataPelajaran\Entities\MataPelajaran;

class StoreMataPelajaranRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $mataPelajarans = MataPelajaran::get();

        return [
            'name' => 'required|string|unique:mata_pelajarans,name',
            'jam_awal' => [
                'required',
                'string',
                function ($jamAwal, $valueInputJamAwal, $fail) use ($mataPelajarans) {
                    $kelas = request()->input('kelas');

                    foreach ($mataPelajarans as $mataPelajaran) {
                        if ($mataPelajaran->kelas === $kelas) {
                            $existingJamAwal = strtotime($mataPelajaran->jam_awal);
                            $existingJamAkhir = strtotime($mataPelajaran->jam_akhir);
                            $inputJamAwal = strtotime($valueInputJamAwal);

                            if ($inputJamAwal >= $existingJamAwal && $inputJamAwal <= $existingJamAkhir) {
                                $fail('Jam mengajar awal sudah terisi!');
                            }
                        }
                    }
                },
            ],
            'jam_akhir' => [
                'required',
                'string',
                function ($jamAkhir, $valueInputJamAkhir, $fail) use ($mataPelajarans) {
                    $kelas = request()->input('kelas');

                    foreach ($mataPelajarans as $mataPelajaran) {
                        if ($mataPelajaran->kelas === $kelas) {
                            $existingJamAwal = strtotime($mataPelajaran->jam_awal);
                            $existingJamAkhir = strtotime($mataPelajaran->jam_akhir);
                            $inputJamAkhir = strtotime($valueInputJamAkhir);

                            if ($inputJamAkhir >= $existingJamAwal && $inputJamAkhir <= $existingJamAkhir) {
                                $fail('Jam mengajar akhir sudah terisi!');
                            }
                        }
                    }

                    $jamAwal = strtotime(request()->input('jam_awal'));
                    if ($jamAwal && (strtotime($valueInputJamAkhir) - $jamAwal) < 1800) {
                        $fail('Jam mengajar minimal 30 menit.');
                    }
                },
            ],
            'kelas' => 'required|string|in:10,11,12',
            'name_guru' => 'nullable|string',
            'materi_pdf' => 'nullable|file|mimes:zip,rar|max:30000',
            'materi_ppt' => 'nullable|file|mimes:zip,rar|max:30000',
            'video' => 'nullable|string',
            'foto' => 'nullable|max:3100|file|mimetypes:image/jpeg,image/png',
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
