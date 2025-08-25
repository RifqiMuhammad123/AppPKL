<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Guru extends Authenticatable
{
    use HasFactory;

    protected $table = 'guru';
    protected $primaryKey = 'id_guru'; // sesuaikan dengan primary key di tabel kamu

    protected $fillable = ['nip', 'nama_guru', 'password'];

    public $timestamps = false; // 🚀 matikan created_at & updated_at

    /** Relasi ke Permintaan */
    public function permintaan()
    {
        return $this->hasMany(Permintaan::class, 'id_guru', 'id_guru');
    }
}
