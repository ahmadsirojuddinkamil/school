<?php

namespace Modules\MataPelajaran\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Guru\Entities\Guru;
use Modules\Siswa\Entities\Siswa;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'name',
        'jam_awal',
        'jam_akhir',
        'kelas',
        'name_guru',
        'materi_pdf',
        'materi_ppt',
        'video',
        'foto',
    ];

    // factory
    protected static function newFactory()
    {
        return \Modules\MataPelajaran\Database\factories\MataPelajaranFactory::new();
    }

    // relasi
    public function siswas()
    {
        return $this->belongsToMany(Siswa::class, 'pivot_mata_pelajaran_siswas', 'mata_pelajaran_uuid', 'siswa_uuid', 'uuid', 'uuid');
    }

    public function guru()
    {
        return $this->hasOne(Guru::class, 'mata_pelajaran_uuid', 'uuid');
    }
}
