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
</div>
@endsection

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
