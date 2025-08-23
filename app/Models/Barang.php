<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'barang';   // nama tabel di database
    protected $primaryKey = 'id_barang'; // primary key
    public $timestamps = false;   // karena di tabelmu tidak ada created_at/updated_at

    protected $fillable = [
        'nama_barang',
        'merk_barang',
        'tanggal_pembelian',
        'asal_usul',
        'harga_barang',
        'stok',
    ];
}
