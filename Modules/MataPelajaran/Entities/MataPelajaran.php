<?php

namespace Modules\MataPelajaran\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $fillable = [];
    
    protected static function newFactory()
    {
        return \Modules\MataPelajaran\Database\factories\MataPelajaranFactory::new();
    }
}
