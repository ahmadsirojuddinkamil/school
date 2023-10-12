<?php

namespace Modules\Absen\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'nisn',
        'status',
        'persetujuan',
        'kehadiran',
    ];

    protected static function AbsenSiswaFactory()
    {
        return \Modules\Absen\Database\factories\AbsenSiswaFactory::new();
    }

    protected static function LaporanZipAbsenSiswaFactory()
    {
        return \Modules\Absen\Database\factories\LaporanZipAbsenSiswaFactory::new();
    }
}
