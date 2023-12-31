<?php

namespace Modules\Ppdb\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpenPpdb extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'tanggal_mulai',
        'tanggal_akhir',
    ];

    protected static function newFactory()
    {
        return \Modules\Ppdb\Database\factories\OpenPpdbFactory::new();
    }
}
