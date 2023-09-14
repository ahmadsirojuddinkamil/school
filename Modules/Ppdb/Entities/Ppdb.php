<?php

namespace Modules\Ppdb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppdb extends Model
{
    use HasFactory;

    protected static function newFactory()
    {
        return \Modules\Ppdb\Database\factories\PpdbFactory::new();
    }

    protected $fillable = [
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
        'tahun_daftar',
        'jurusan',
        'nama_ayah',
        'nama_ibu',
        'telpon_orang_tua',
        'bukti_pendaftaran',
    ];
}
