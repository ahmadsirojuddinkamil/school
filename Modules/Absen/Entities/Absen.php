<?php

namespace Modules\Absen\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;

class Absen extends Model
{
    use HasFactory;

    protected $fillable = [
        'siswa_uuid',
        'guru_uuid',
        'uuid',
        'status',
        'keterangan',
        'persetujuan',
    ];

    // factory
    protected static function AbsenSiswaFactory()
    {
        return \Modules\Absen\Database\factories\AbsenSiswaFactory::new();
    }

    protected static function LaporanZipAbsenSiswaFactory()
    {
        return \Modules\Absen\Database\factories\LaporanZipAbsenSiswaFactory::new();
    }

    // relasi
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
