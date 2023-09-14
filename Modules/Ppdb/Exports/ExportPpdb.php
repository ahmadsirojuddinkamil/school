<?php

namespace Modules\Ppdb\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Ppdb\Entities\Ppdb;

class ExportPpdb implements FromCollection, WithHeadings
{
    protected $saveParameterDateFromController;

    public function __construct($saveParameterDateFromController)
    {
        $this->saveParameterDateFromController = $saveParameterDateFromController;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Ppdb::select(
            'uuid',
            'nama_lengkap',
            'email',
            'nisn',
            'asal_sekolah',
            'alamat',
            'telpon_siswa',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'jurusan',
            'nama_ayah',
            'nama_ibu',
            'telpon_orang_tua',
            'tahun_daftar'
        )
            ->where('tahun_daftar', $this->saveParameterDateFromController)
            ->get();
    }

    public function headings(): array
    {
        return [
            'UUID',
            'Nama Lengkap',
            'Email',
            'NISN',
            'Asal Sekolah',
            'Alamat',
            'Telpon Siswa',
            'Jenis Kelamin',
            'Tempat Lahir',
            'Tanggal Lahir',
            'Jurusan',
            'Nama Ayah',
            'Nama Ibu',
            'Telpon Orang Tua',
            'Tahun Daftar',
        ];
    }
}
