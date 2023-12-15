<?php

namespace Modules\Siswa\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Siswa\Entities\Siswa;

class ExportSiswaActive implements FromCollection, WithHeadings
{
    protected $uuidSiswa;

    public function __construct($saveUuidFromCall)
    {
        $this->uuidSiswa = $saveUuidFromCall;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Siswa::select(
            'name',
            'nisn',
            'kelas',
            'tempat_lahir',
            'tanggal_lahir',
            'agama',
            'jenis_kelamin',
            'asal_sekolah',
            'nem',
            'tahun_lulus',
            'alamat_rumah',
            'provinsi',
            'kecamatan',
            'kelurahan',
            'kode_pos',
            'email',
            'no_telpon',
            'tahun_daftar',
            'tahun_keluar',
            'nama_bank',
            'nama_buku_rekening',
            'no_rekening',
            'nama_ayah',
            'nama_ibu',
            'nama_wali',
            'telpon_orang_tua',
        )
            ->where('uuid', $this->uuidSiswa)
            ->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'nisn',
            'kelas',
            'tempat_lahir',
            'tanggal_lahir',
            'agama',
            'jenis_kelamin',
            'asal_sekolah',
            'nem',
            'tahun_lulus',
            'alamat_rumah',
            'provinsi',
            'kecamatan',
            'kelurahan',
            'kode_pos',
            'email',
            'no_telpon',
            'tahun_daftar',
            'tahun_keluar',
            'nama_bank',
            'nama_buku_rekening',
            'no_rekening',
            'nama_ayah',
            'nama_ibu',
            'nama_wali',
            'telpon_orang_tua',
        ];
    }
}
