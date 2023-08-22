<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ppdb extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid_payment',
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
        'jurusan',
        'nama_ayah',
        'nama_ibu',
        'telpon_orang_tua',
        'tahun_daftar',
        'bukti_pendaftaran',
    ];

    // relasi
    public function payments()
    {
        return $this->hasMany(Payment::class, 'uuid_ppdb', 'uuid');
    }
}
