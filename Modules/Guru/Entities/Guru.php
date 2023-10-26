<?php

namespace Modules\Guru\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Absen\Entities\Absen;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'uuid',
        'name',
        'nuptk',
        'nip',
        'tempat_lahir',
        'tanggal_lahir',
        'mata_pelajaran',
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
        'foto',
        'nama_bank',
        'nama_buku_rekening',
        'no_rekening',
    ];

    protected static function newFactory()
    {
        return \Modules\Guru\Database\factories\GuruFactory::new();
    }

    // relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function absens()
    {
        return $this->hasMany(Absen::class);
    }
}
