<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class Admin extends Model
{
    // Nama tabel
    protected $table = 'admin';

    // Primary key
    protected $primaryKey = 'id_admin';

    // Nonaktifkan timestamps (karena tabel tidak punya created_at & updated_at)
    public $timestamps = false;

    // Kolom yang bisa diisi
    protected $fillable = [
        'nip',
        'nama_admin',
        'password',
        'password_encrypted',
        'foto',
    ];

    // Kolom yang disembunyikan saat model diubah ke array/JSON
    protected $hidden = ['password'];

    /**
     * Mutator: ketika password diset, otomatis di-hash dan disimpan versi terenkripsi juga.
     */
    public function setPasswordAttribute($value)
    {
        if (empty($value)) return;

        // Hash untuk autentikasi (tidak bisa di-decrypt)
        $this->attributes['password'] = Hash::make($value);

        // Simpan versi terenkripsi (bisa di-decrypt bila diperlukan)
        try {
            $this->attributes['password_encrypted'] = Crypt::encryptString($value);
        } catch (\Exception $e) {
            // Jika enkripsi gagal, kosongkan kolom agar tidak error
            $this->attributes['password_encrypted'] = null;
        }
    }
}
