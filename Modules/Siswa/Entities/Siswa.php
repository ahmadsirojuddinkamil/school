<?php

namespace Modules\Siswa\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

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

    protected $fillable = [
        'uuid',
        'user_id',

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
        'jurusan',
        'nama_ayah',
        'nama_ibu',
        'telpon_orang_tua',
        'foto',
    ];

    // relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
