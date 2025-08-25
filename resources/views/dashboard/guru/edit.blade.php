@extends('layouts.admin-shell') {{-- sesuaikan dengan layout utama kamu --}}

@section('content')
<!-- <link rel="stylesheet" href="{{ asset('css/form-barang.css') }}"> -->
<div class="container">
    <h2>Edit Data Guru</h2>
    <style>
        /* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
}

/* Container utama */
.container {
  max-width: 450px;
  margin: 40px auto;
  padding: 25px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
}

/* Judul */
.container h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #000000ff;
  font-size: 22px;
  font-weight: 600;
}

/* Form Group */
.form-group {
  margin-bottom: 18px;
}

.form-group label {
  display: block;
  margin-bottom: 6px;
  font-size: 14px;
  font-weight: 500;
  color: #333;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-size: 14px;
  transition: 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2a5298;
  outline: none;
  box-shadow: 0 0 6px rgba(42,82,152,0.3);
}

/* Tombol */
button,
.btn {
  display: inline-block;
  background: #2a5298;
  color: #fff;
  padding: 10px 18px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.3s ease;
}

button:hover,
.btn:hover {
  background: #1e3c72;
}

    </style>

    <form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="nip">NIP</label>
            <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label for="nama_guru">Nama Guru</label>
            <input type="text" name="nama_guru" value="{{ old('nama_guru', $guru->nama_guru) }}" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
