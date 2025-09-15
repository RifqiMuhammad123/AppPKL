@extends('layouts.admin-shell')
@section('title','Tambah Barang')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form-barang.css') }}">

<div class="form-container">
    <h2>Tambah Barang</h2>
    <!-- Ganti route('barang.store') -> admin.barang.store -->
    <form id="form-tambah" action="{{ route('admin.barang.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" required>
        </div>
        <div class="form-group">
            <label>Merk Barang</label>
            <input type="text" name="merk_barang" required>
        </div>
        <div class="form-group">
            <label>Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" required>
        </div>
        <!-- <div class="form-group">
            <label>Asal Usul</label>
            <input type="text" name="asal_usul" required>
        </div> -->
        <div class="form-group">
            <label>Harga Barang</label>
            <input type="number" name="harga_barang" required>
        </div>
        <div class="form-group">
            <label>Jumlah Stok</label>
            <input type="number" name="stok" required>
        </div>

        <div class="form-actions">
            <!-- Ganti route('barang.index') -> admin.barang.index -->
            <a href="{{ route('admin.barang.index') }}" class="btn btn-secondary">Kembali</a>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
    </form>
</div>

<script>
document.getElementById('form-tambah').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin menambahkan barang ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, Tambah'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>
@endsection
