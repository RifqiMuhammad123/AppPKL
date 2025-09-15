@extends('layouts.admin-shell')
@section('title','Dashboard Admin')

@section('content')

<div class="cards">

    <!-- Card All Stok -->
    <div class="card">
        <div class="card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
        <div class="card-meta">
            <h4>All Stok Barang</h4>
            <p>Total stok saat ini</p>
            <div class="stat">{{ number_format($allStok) }}</div>
        </div>
    </div>

    <!-- Card Barang Baru -->
    <div class="card">
        <div class="card-icon"><i class="fa-solid fa-star"></i></div>
        <div class="card-meta">
            <h4>Barang Baru</h4>
            <p>3 hari terakhir</p>
            <div class="stat">{{ number_format($barangBaru) }}</div>
        </div>
    </div>

    <!-- Card Permintaan (klikable) -->
    <a href="{{ route('admin.permintaan.index') }}" class="card card-clickable">
        <div class="card-icon">
            <i class="fa-solid fa-inbox"></i>
            <span id="notif-badge" class="notif-badge" style="display:none;">0</span>
        </div>
        <div class="card-meta">
            <h4>Permintaan</h4>
            <p>Total permintaan</p>
            <div class="stat" id="notif-count">{{ number_format($permintaan) }}</div>
        </div>
    </a>

</div>

<div class="panel">
    <h4>Barang Terbaru</h4>
    <table class="table">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Merk</th>
                <th>Tgl Beli</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barangTerbaru as $index => $b)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $b->nama_barang }}</td>
                    <td>{{ $b->merk_barang }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</td>
                   
                    <td>Rp {{ number_format($b->harga_barang,0,',','.') }}</td>
                    <td>{{ $b->stok }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Belum ada data.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@endsection

@section('scripts')
<script>
    function loadNotif() {
        fetch("{{ route('permintaan.notif') }}")
            .then(res => res.json())
            .then(data => {
                document.getElementById("notif-count").innerText = data.pending;

                let badge = document.getElementById("notif-badge");
                if (data.pending > 0) {
                    badge.style.display = "inline-block";
                    badge.innerText = data.pending;
                } else {
                    badge.style.display = "none";
                }
            })
            .catch(err => console.error(err));
    }

    // panggil langsung saat load
    loadNotif();
    // auto refresh tiap 3 detik
    setInterval(loadNotif, 3000);
</script>

<style>
.notif-badge {
    position: absolute;
    top: -5px;
    right: -8px;
    background: #ef4444;
    color: #fff;
    font-size: 12px;
    padding: 2px 6px;
    border-radius: 50%;
    font-weight: bold;
}
.card-icon {
    position: relative;
}
</style>
@endsection
