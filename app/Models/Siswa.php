<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid_payment',
        'uuid',
        'nama_lengkap',
        'email',
        'password',
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
    ];

    // relasi
    public function payments()
    {
        return $this->hasMany(Payment::class, 'uuid_payment');
    }
}
