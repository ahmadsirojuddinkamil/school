<?php

namespace Modules\Absen\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ExportAbsen implements FromCollection, WithHeadings
{
    protected $dataAbsen;

    public function __construct($saveDataFromCall)
    {
        $this->dataAbsen = $saveDataFromCall;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return collect($this->dataAbsen)->map(function ($absen) {
            return [
                'updated_at' => $absen['updated_at'],
                'keterangan' => $absen['keterangan'],
            ];
        });
    }

    public function headings(): array
    {
        return [
            'waktu & tanggal',
            'keterangan',
        ];
    }
}
