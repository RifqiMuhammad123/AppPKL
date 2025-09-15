@extends('layouts.guru-shell')
@section('title','Daftar Barang')

@section('content')
<div class="barang-container">
    <h2>Daftar Barang</h2>

    <table class="barang-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Tanggal</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $index => $b)
            <tr class="clickable-row" data-href="{{ route('guru.permintaan.fromBarang', $b->id_barang) }}">
                <td>{{ $index + 1 }}</td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->merk_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</td>
                <td>Rp {{ number_format($b->harga_barang,0,',','.') }}</td>
                <td>{{ $b->stok }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; color:#888;">Belum ada barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
.barang-container {
    background: #fff;
    border-radius: 12px;
    padding: 20px;
    margin-top: 30px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
}

.barang-container h2 {
    margin-bottom: 15px;
    font-size: 20px;
    font-weight: bold;
    color: #333;
}

.barang-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
}

.barang-table th, .barang-table td {
    padding: 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

.barang-table th {
    background: #f8f9fa;
    font-weight: bold;
}

.barang-table tr:hover {
    background-color: #eef5ff;
    cursor: pointer;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".clickable-row").forEach(row => {
        row.addEventListener("click", function() {
            window.location = this.dataset.href;
        });
    });
});
</script>
@endsection
