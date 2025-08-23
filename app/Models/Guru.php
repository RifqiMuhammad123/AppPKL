<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    protected $fillable = ['nip','nama_guru','password'];

    /** Relasi ke Permintaan */
    public function permintaan()
    {
        return $this->hasMany(Permintaan::class, 'id_guru', 'id_guru');
    }
}
