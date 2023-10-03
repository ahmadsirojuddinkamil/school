<?php

namespace Modules\Siswa\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Siswa\Entities\Siswa;

class ExportSiswaActive implements FromCollection, WithHeadings
{
    protected $classSiswa;

    public function __construct($saveClassFromObjectCaller)
    {
        $this->classSiswa = $saveClassFromObjectCaller;
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
            'kelas',
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
            ->where('kelas', $this->classSiswa)
            ->get();
    }

    public function headings(): array
    {
        return [
            'nama_lengkap',
            'email',
            'nisn',
            'asal_sekolah',
            'kelas',
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
