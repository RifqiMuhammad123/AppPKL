<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    // Nama tabel di DB
    protected $table = 'permintaan';

    // Primary key sesuai database
    protected $primaryKey = 'id_permintaan';

    // Primary key auto increment
    public $incrementing = true;

    // Tipe primary key (integer)
    protected $keyType = 'int';

    // Aktifkan timestamps (created_at, updated_at)
    public $timestamps = true;

    // Kolom yang bisa diisi (mass assignment)
    protected $fillable = [
        'id_guru',
        'nama_barang',
        'merk_barang',
        'tanggal',
        'jumlah',
        'status',
    ];

    /**
     * Relasi ke tabel guru
     * 1 permintaan dimiliki oleh 1 guru
     */
    public function guru()
{
    return $this->belongsTo(\App\Models\Guru::class, 'id_guru', 'id_guru');
}


}
