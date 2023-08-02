<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'uuid_siswa',
        'bukti_pendaftaran_siswa_baru',
    ];

    // relasi
    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'uuid_siswa');
    }
}
