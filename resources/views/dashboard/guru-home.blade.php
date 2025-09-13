@extends('layouts.guru-shell')

@section('title', 'Dashboard Guru')

@section('content')
<div class="cards">

  <!-- Card 1 -->
  <div class="card" onclick="window.location.href='{{ route("guru.barang.index") }}'">
    <div class="card-icon"><i class="fa-solid fa-box"></i></div>
    <div class="card-meta">
      <h4>All Stok Barang</h4>
      <p>Lihat semua barang tersedia</p>
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
@endsection
