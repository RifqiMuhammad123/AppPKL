@extends('layouts.guru-shell')

@section('title', 'Diproses')

@section('content')
<div class="panel">
    <h2>Status Permintaan Barang</h2>

    <table class="table" id="tabel-permintaan">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($permintaan as $p)
                <tr>
                    <td>{{ $p->nama_barang }}</td>
                    <td>{{ $p->merk_barang }}</td>
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->jumlah }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span class="badge pending">Pending</span>
                        @elseif($p->status == 'dikonfirmasi')
                            <span class="badge success">✔ Diterima</span>
                        @elseif($p->status == 'ditolak')
                            <span class="badge danger">✘ Ditolak</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

   <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('guru.home') }}'">Kembali</button>
</div>
@endsection

@section('styles')
<style>
.btn-secondary {
        background: #f44336; /* merah */
        color: #fff;
     }

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 16px rgba(244,67,54,.35);
}
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

 </style>

@section('scripts')
<script>
function loadStatus() {
    fetch("{{ route('guru.permintaan.status') }}", { cache: "no-store" })
        .then(res => res.json())
        .then(data => {
            let tbody = document.querySelector("#tabel-permintaan tbody");
            tbody.innerHTML = "";
            data.forEach(p => {
                let statusBadge = "";
                if (p.status === "pending") {
                    statusBadge = '<span class="badge pending">Pending</span>';
                } else if (p.status === "dikonfirmasi") {
                    statusBadge = '<span class="badge success">✔ Diterima</span>';
                } else if (p.status === "ditolak") {
                    statusBadge = '<span class="badge danger">✘ Ditolak</span>';
                }

                tbody.innerHTML += `
                    <tr>
                        <td>${p.nama_barang}</td>
                        <td>${p.merk_barang}</td>
                        <td>${p.tanggal}</td>
                        <td>${p.jumlah}</td>
                        <td>${statusBadge}</td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error("Status error:", err));
}
loadStatus();
setInterval(loadStatus, 3000);
</script>
@endsection
