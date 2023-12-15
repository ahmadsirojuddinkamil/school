<?php

namespace Modules\Siswa\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Absen\Entities\Absen;
use Modules\MataPelajaran\Entities\MataPelajaran;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_uuid',
        'mata_pelajaran_uuid',
        'uuid',
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
        'foto',
        'nama_bank',
        'nama_buku_rekening',
        'no_rekening',
        'nama_ayah',
        'nama_ibu',
        'nama_wali',
        'telpon_orang_tua',
    ];

    // factory
    protected static function SiswaActiveFactory()
    {
        return \Modules\Siswa\Database\factories\SiswaActiveFactory::new();
    }

    protected static function SiswaGraduatedFactory()
    {
        return \Modules\Siswa\Database\factories\SiswaGraduatedFactory::new();
    }

    protected static function SiswaAbsenFactory()
    {
        return \Modules\Siswa\Database\factories\SiswaAbsenFactory::new();
    }

    protected static function AdminAbsenFactory()
    {
        return \Modules\Siswa\Database\factories\AdminAbsenFactory::new();
    }

    // relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absens()
    {
        return $this->hasMany(Absen::class, 'siswa_uuid', 'uuid');
    }

    public function mataPelajarans()
    {
        return $this->belongsToMany(MataPelajaran::class, 'pivot_mata_pelajaran_siswas', 'siswa_uuid', 'mata_pelajaran_uuid', 'uuid', 'uuid');
    }
}
