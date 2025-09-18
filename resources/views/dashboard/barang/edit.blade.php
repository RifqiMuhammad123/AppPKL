@extends('layouts.admin-shell')
@section('title','Edit Barang')

@section('content')
<link rel="stylesheet" href="{{ asset('css/form-barang.css') }}">

<div class="form-container">
    <h2>Edit Barang</h2>
    <form id="form-edit" 
          action="{{ route('admin.barang.update', $barang->id_barang) }}" 
          method="POST" 
          enctype="multipart/form-data"> {{-- ✅ penting untuk upload foto --}}

        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Foto Barang (Opsional)</label><br>
            @if($barang->foto)
                <img src="{{ asset('storage/'.$barang->foto) }}" 
                     alt="Foto lama" 
                     style="max-width:100px; max-height:100px; object-fit:cover; margin-bottom:8px;">
            @endif
            <input type="file" name="foto" accept="image/*">
            <small class="text-muted">Kosongkan jika tidak ingin mengganti foto.</small>
        </div>

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" required>
        </div>
        <div class="form-group">
            <label>Merk Barang</label>
            <input type="text" name="merk_barang" value="{{ $barang->merk_barang }}" required>
        </div>
        <div class="form-group">
            <label>Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" value="{{ $barang->tanggal_pembelian }}" required>
        </div>
        <div class="form-group">
            <label>Harga Barang</label>
            <input type="number" name="harga_barang" value="{{ $barang->harga_barang }}" required>
        </div>
        <div class="form-group">
            <label>Jumlah Stok</label>
            <input type="number" name="stok" value="{{ $barang->stok }}" required>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('admin.barang.index') }}'">Kembali</button>
            <button type="submit" class="btn btn-primary">Update</button>
        </div>
    </form>
</div>

<script>
document.getElementById('form-edit').addEventListener('submit', function(e) {
    e.preventDefault();
    Swal.fire({
        title: 'Konfirmasi',
        text: 'Apakah Anda yakin ingin mengupdate data barang ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#7c3aed',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Ya, Update'
    }).then((result) => {
        if (result.isConfirmed) {
            e.target.submit();
        }
    });
});
</script>
@endsection
