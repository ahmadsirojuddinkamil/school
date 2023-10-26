<?php

namespace Modules\Guru\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Modules\Guru\Entities\Guru;

class ExportExcelBiodataGuru implements FromCollection, WithHeadings
{
    protected $uuidGuru;

    public function __construct($saveUuidFromCall)
    {
        $this->uuidGuru = $saveUuidFromCall;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Guru::select(
            'name',
            'nuptk',
            'nip',
            'tempat_lahir',
            'tanggal_lahir',
            // 'mata_pelajaran',
            'agama',
            'jenis_kelamin',
            'status_perkawinan',
            'jam_mengajar',
            'pendidikan_terakhir',
            'nama_tempat_pendidikan',
            'ipk',
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
        )
            ->where('uuid', $this->uuidGuru)
            ->get();
    }

    public function headings(): array
    {
        return [
            'name',
            'nuptk',
            'nip',
            'tempat_lahir',
            'tanggal_lahir',
            // 'mata_pelajaran',
            'agama',
            'jenis_kelamin',
            'status_perkawinan',
            'jam_mengajar',
            'pendidikan_terakhir',
            'nama_tempat_pendidikan',
            'ipk',
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
        ];
    }
}
