<?php

namespace Modules\Ppdb\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Ppdb\Entities\Ppdb;

class ExportPpdbZip implements FromCollection, WithHeadings
{
    protected $uuidPpdb;

    public function __construct($saveUuidFromCall)
    {
        $this->uuidPpdb = $saveUuidFromCall;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Ppdb::select(
            'name',
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
            ->where('uuid', $this->uuidPpdb)
            ->get();
    }

    public function headings(): array
    {
        return [
            'Nama',
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
