@extends('layouts.guru-shell')

@section('title','Ajukan Permintaan')

<link rel="stylesheet" href="{{ asset('css/form.css') }}">

@section('content')
<div class="form-container">
    <h2>Ajukan Permintaan Barang</h2>

    <form action="{{ route('guru.permintaan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" 
                   value="{{ $barang->nama_barang ?? '' }}" 
                   {{ isset($barang) ? 'readonly' : '' }} required>
        </div>

        <div class="form-group">
            <label>Merk Barang</label>
            <input type="text" name="merk_barang" 
                   value="{{ $barang->merk_barang ?? '' }}" 
                   {{ isset($barang) ? 'readonly' : '' }} required>
        </div>

        <div class="form-group">
            <label>Tanggal Permintaan</label>
            <input type="date" name="tanggal" required>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1" required>
        </div>

        <div class="form-actions">
            <a href="{{ route('guru.home') }}" class="btn btn-secondary">Kembali</a>
        <button type="submit" class="btn-submit">Ajukan</button>
    </form>
    </div>
</div>
@endsection
