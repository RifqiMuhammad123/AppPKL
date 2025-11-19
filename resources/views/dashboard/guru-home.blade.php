@extends('layouts.guru-shell')

@section('title', 'Dashboard Guru')

<link rel="stylesheet" href="{{ asset('css/daftar-barang-guru.css') }}">

@section('content')
<div class="cards">

  <!-- Card 1 -->
    <div class="card">
        <div class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="card-meta">
            <h4>All Stok Barang</h4>
            <p>Semua barang yang tersedia</p>
            <div class="stat">{{ number_format($allStok) }}</div>
        </div>
    </div>

  <!-- Card 2 -->
  <div class="card" onclick="window.location.href='{{ route("guru.permintaan.create") }}'">
    <div class="card-icon"><i class="fa-solid fa-clipboard-list"></i></div>
    <div class="card-meta">
      <h4>Permintaan Barang</h4>
      <p>Ajukan barang ke Admin</p>
    </div>
  </div>

  <!-- Card 3 -->
  <div class="card" onclick="window.location.href='{{ route("guru.permintaan.proses") }}'">
    <div class="card-icon"><i class="fa-solid fa-hourglass-half"></i></div>
    <div class="card-meta">
      <h4>Status Permintaan</h4>
      <p>Diproses</p>
    </div>
  </div>

</div>

<!-- Daftar Barang -->
<div class="barang-container mt-5">
    <h2>Daftar Barang</h2>

    <table class="barang-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
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
                <td style="width:120px; text-align:center;">
                    @if($b->foto)
                        <img src="{{ asset('storage/'.$b->foto) }}" 
                            alt="Foto {{ $b->nama_barang }}" 
                            style="max-width:100px; max-height:100px; object-fit:cover; border-radius:5px;">
                    @else
                        <span>-</span>
                    @endif
                </td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->merk_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</td>
                <td>Rp {{ number_format($b->harga_barang,0,',','.') }}</td>
                <td>{{ $b->stok }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-muted text-center">Belum ada barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".clickable-row").forEach(row => {
        row.addEventListener("click", function() {
            window.location = this.dataset.href;
        });
    });
});
</script>
@endpush