@extends('layouts.guru-shell')

@section('title','Ajukan Permintaan Barang')

@section('content')
<div class="form-container">
    <h2>Ajukan Permintaan Barang</h2>

    <form action="{{ route('guru.permintaan.storeFromBarang', $barang->id_barang) }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Barang</label>
            <input type="text" value="{{ $barang->nama_barang }}" readonly>
        </div>

        <div class="form-group">
            <label>Merk Barang</label>
            <input type="text" value="{{ $barang->merk_barang }}" readonly>
        </div>

        <div class="form-group">
            <label>Tanggal Permintaan</label>
            <input type="date" name="tanggal" required>
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" min="1" max="{{ $barang->stok }}" required>
        </div>
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('guru.home') }}'">Kembali</button>
        <button type="submit" class="btn-submit">Ajukan Permintaan</button>
    </form>
</div>

<style>
.form-container {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    max-width: 500px;
}
.form-container h2 {
    margin-bottom: 15px;
}
.form-group {
    margin-bottom: 12px;
}
.form-group label {
    font-weight: bold;
    display: block;
    margin-bottom: 6px;
}
.form-group input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 6px;
}
.btn-submit {
    background: #4CAF50;
    color: white;
    padding: 10px 16px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
}
/* ===== Tombol (Submit & Secondary) ===== */
.btn,
.btn-submit,
.btn-primary,
.btn-secondary {
    margin-top: 20px;
    padding: 8px 18px;
    border-radius: 8px;
    font-size: 13px;
    border: none;
    cursor: pointer;
    transition: transform 0.15s ease, box-shadow 0.2s ease;
    text-align: center;
}

/* Primary */
.btn-primary,
.btn-submit {
    background: linear-gradient(90deg, var(--accent), var(--accent2));
    color: #fff;
}

.btn-primary:hover,
.btn-submit:hover {
    transform: translateY(-2px);
    box-shadow:0 6px 16px rgba(30,136,229,.35);
}

/* Secondary */
.btn-secondary {
    background: #f44336; /* merah */
    color: #fff;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(244,67,54,.35);
}
</style>
@endsection
