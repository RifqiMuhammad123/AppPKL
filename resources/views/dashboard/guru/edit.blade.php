@extends('layouts.admin-shell') {{-- sesuaikan dengan layout utama kamu --}}

@section('content')
<div class="container">
    <h2>Edit Data Guru</h2>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

:root {
  --accent: #7c3aed;
  --accent2: #a855f7;
  --muted: #7b7193;
  --radius: 8px;
}

/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Poppins', sans-serif;
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
  border-color: var(--accent);
  outline: none;
  box-shadow: 0 0 6px rgba(42,82,152,0.3);
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
  box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
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
