@extends('layouts.admin-shell') {{-- sesuaikan dengan layout utama kamu --}}

@section('content')
<div class="container">
    <h2>Edit Data Guru</h2>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

:root {
  --accent: #1e88e5;
  --accent2: #42a5f5;
  --muted: #7b7193;
  --radius: 8px;
}

/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial;
}

/* Container */
.container {
  max-width: 500px;
  margin: 50px auto;
  padding: 30px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
  animation: fadeIn 0.3s ease;
}

/* Animasi masuk */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Judul */
.container h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #222;
  font-size: 22px;
  font-weight: 600;
}

.form-container {
    background: var(--card);
    border-radius: var(--radius);
    padding: 18px;
    max-width: 400px; /* lebih kecil */
    margin: 0 auto;
    box-shadow: 0 4px 16px rgba(9, 9, 40, 0.04);
    border: 1px solid #eee;
}

.form-container h2 {
    margin: 0 0 16px;
    font-size: 18px; /* lebih kecil */
    font-weight: 700;
    color: #000000;
    text-align: center;
}

.form-group {
    margin-bottom: 14px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 14px; /* lebih kecil */
    color: var(--muted);
    margin-bottom: 4px;
}

.form-group input {
    border: none;
    border-bottom: 2px solid #ddd;
    padding: 6px 4px; /* lebih kecil */
    font-size: 13px;
    background: transparent;
    outline: none;
    transition: border-color 0.2s ease;
}

.form-group input:focus {
    border-color: var(--accent);
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 14px;
}

/* Tombol */
.btn {
  padding: 10px 16px;
  border-radius: var(--radius);
  font-size: 14px;
  border: none;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.15s ease;
}

.btn-primary {
  background: linear-gradient(90deg, var(--accent), var(--accent2));
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow:0 6px 16px rgba(30,136,229,.35);
}

.btn-secondary {
  background: #ddd;
  color: #333;
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-secondary:hover {
  background: #ccc;
  transform: translateY(-2px);
}

/* Tombol container */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  margin-top: 20px;
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
