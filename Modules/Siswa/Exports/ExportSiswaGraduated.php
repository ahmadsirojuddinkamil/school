<?php

namespace Modules\Siswa\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Siswa\Entities\Siswa;

class ExportSiswaGraduated implements FromCollection, WithHeadings
{
    protected $yearGraduated;

    public function __construct($saveYearGraduatedFromObjectCaller)
    {
        $this->yearGraduated = $saveYearGraduatedFromObjectCaller;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::select(
            'nama_lengkap',
            'email',
            'nisn',
            'asal_sekolah',
            'alamat',
            'telpon_siswa',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'tahun_daftar',
            'tahun_lulus',
            'jurusan',
            'nama_ayah',
            'nama_ibu',
            'telpon_orang_tua',
        )
            ->where('tahun_lulus', $this->yearGraduated)
            ->get();
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'email',
            'nisn',
            'asal_sekolah',
            'alamat',
            'telpon_siswa',
            'jenis_kelamin',
            'tempat_lahir',
            'tanggal_lahir',
            'tahun_daftar',
            'tahun_lulus',
            'jurusan',
            'nama_ayah',
            'nama_ibu',
            'telpon_orang_tua',
        ];
    }
}
