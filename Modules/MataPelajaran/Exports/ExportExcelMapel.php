<?php

namespace Modules\MataPelajaran\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Guru\Entities\Guru;
use Modules\MataPelajaran\Entities\MataPelajaran;

class ExportExcelMapel implements FromCollection, WithHeadings
{
    protected $uuidMapel;

    public function __construct($saveUuidFromCall)
    {
        $this->uuidMapel = $saveUuidFromCall;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return MataPelajaran::select(
            'name',
            'jam_awal',
            'jam_akhir',
            'kelas',
            'name_guru',
        )
            ->where('uuid', $this->uuidMapel)
            ->get();
    }

    public function headings(): array
    {
        return [
            'nama',
            'jam awal',
            'jam akhir',
            'kelas',
            'pengajar',
        ];
    }
}
