@extends('layouts.guru-shell')
@section('title', 'Diproses')

@section('content')

<div class="permintaan-container">

    <div class="header-flex">
        <button class="btn-back" onclick="window.location='{{ route('guru.home') }}'">
            <i class="fa-solid fa-arrow-left"></i>
        </button>
        <h2>Status Permintaan Barang</h2>
    </div>

    <div class="panel">
        <table class="table" id="tabel-permintaan">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>

                {{-- LOAD AWAL (SERVER SIDE) --}}
                @forelse($permintaan as $index => $p)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $p->nama_barang }}</td>
                        <td>{{ $p->merk_barang }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                        <td>{{ $p->jumlah }}</td>
                        <td>
                            @if($p->status == 'pending')
                                <span class="badge pending">⏳ Pending</span>
                            @elseif($p->status == 'dikonfirmasi')
                                <span class="badge success">✔ Diterima</span>
                            @elseif($p->status == 'ditolak')
                                <span class="badge danger">✘ Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($p->status == 'ditolak' && $p->catatan && $p->catatan != '_')
                                <button class="btn-lihat-catatan" onclick="showCatatan('{{ addslashes($p->catatan) }}')">
                                    <i class="fa-solid fa-eye"></i> Lihat Catatan
                                </button>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="empty-row">
                        <td colspan="7" class="empty-state">
                            <i class="fa-solid fa-inbox"></i>
                            <p>Tidak ada permintaan yang sedang diproses</p>
                        </td>
                    </tr>
                @endforelse

            </tbody>
        </table>
    </div>
</div>

{{-- MODAL CATATAN --}}
<div id="modal-catatan" class="modal-catatan">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fa-solid fa-comment-dots"></i> Catatan Penolakan</h3>
            <button class="btn-close" onclick="closeCatatan()">&times;</button>
        </div>
        <div class="modal-body">
            <p id="catatan-text"></p>
        </div>
    </div>
</div>


{{-- ========================== CSS ========================== --}}
<style>

/* BADGES */
.badge { padding:5px 12px; border-radius:6px; font-size:13px; font-weight:500; }
.badge.pending { background:#fff3cd; color:#856404; }
.badge.success { background:#d4edda; color:#155724; }
.badge.danger  { background:#f8d7da; color:#721c24; }

/* BUTTON LIHAT CATATAN */
.btn-lihat-catatan {
    background:#dc3545; color:#fff; border:none;
    padding:6px 12px; border-radius:5px; cursor:pointer;
    display:inline-flex; align-items:center; gap:5px;
    font-size:12px; transition:.3s;
}
.btn-lihat-catatan:hover { background:#c82333; transform:scale(1.05); }

/* BACK BUTTON */
.btn-back {
    background:#f44336; color:#fff; border:none;
    width:42px; height:42px; border-radius:50%;
    display:flex; align-items:center; justify-content:center;
    font-size:18px; cursor:pointer; margin-right:12px;
    box-shadow:0 2px 6px rgba(0,0,0,0.2); transition:.25s ease;
}
.btn-back:hover { background:#c62828; transform:translateY(-2px); }

/* HEADER FLEX */
.header-flex { display:flex; align-items:center; margin-bottom:18px; }

/* EMPTY STATE */
.empty-state { text-align:center; padding:45px 0; }
.empty-state i { font-size:46px; opacity:.45; }
.empty-state p { margin-top:8px; color:#666; }

/* MODAL */
.modal-catatan {
    display:none; position:fixed; inset:0;
    background:rgba(0,0,0,0.5); justify-content:center; align-items:center;
}
.modal-content { background:white; width:90%; max-width:450px; border-radius:12px; }
.modal-header { padding:20px; border-bottom:1px solid #eee; display:flex; justify-content:space-between; }
.modal-body { padding:20px; }
.btn-close { font-size:26px; background:none; border:none; cursor:pointer; }

/* ===================================================== */
/*      🔥 FIX UTAMA — Background container kembali       */
/* ===================================================== */

.permintaan-container {
    background: #fff;
    padding: 22px 28px;
    border-radius: 14px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.06);
    margin-top: 18px;
}

/* ===================================================== */
/*      🔥 CLOSE-TO-UI: TABEL DENGAN BORDER RADIUS       */
/* ===================================================== */

/* WRAPPER */
.panel {
    background:none !important;
    padding:0 !important;
    border:none !important;
    box-shadow:none !important;
}

/* TABEL */
.table {
    width:100%;
    border-collapse:separate !important; /* wajib biar bisa radius */
    border-spacing:0 !important;         /* nolkan celah */
    overflow:hidden;
    border-radius:12px;                  /* radius full */
    background:white;
    box-shadow:0 1px 4px rgba(0,0,0,0.08);
}

/* HEADER */
.table thead th {
    background:#0055cc !important;
    color:#fff !important;
    font-weight:600;
    padding:12px 10px;
    border-bottom:2px solid #004bb3;
    text-align:center;
    font-size:13px;
}

/* Radius pojok header */
.table thead th:first-child {
    border-top-left-radius:12px;
}
.table thead th:last-child {
    border-top-right-radius:12px;
}

/* BODY */
.table tbody td {
    background:white !important;
    padding:12px 10px;
    border-bottom:1px solid #eee;
    font-size:13px;
    text-align:center;
}

/* Radius pojok baris terakhir */
.table tbody tr:last-child td:first-child {
    border-bottom-left-radius:12px;
}
.table tbody tr:last-child td:last-child {
    border-bottom-right-radius:12px;
}

/* Hover */
.table tbody tr:hover td {
    background:#eef5ff !important;
}


</style>



@endsection



{{-- ========================== JS ========================== --}}
@section('scripts')
<script>

// === MODAL CATATAN ===
function showCatatan(catatan){
    document.getElementById('catatan-text').textContent = catatan;
    document.getElementById('modal-catatan').style.display = 'flex';
}
function closeCatatan(){
    document.getElementById('modal-catatan').style.display = 'none';
}
window.onclick = function(e){
    if(e.target == document.getElementById('modal-catatan')) closeCatatan();
}


// === AUTO REFRESH STATUS ===
function loadStatus() {
    fetch("{{ route('guru.permintaan.status') }}", { cache: "no-store" })
        .then(res => res.json())
        .then(data => {

            let tbody = document.querySelector("#tabel-permintaan tbody");
            tbody.innerHTML = "";

            // *** EMPTY STATE ***
            if (data.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="empty-state">
                            <i class="fa-solid fa-inbox"></i>
                            <p>Tidak ada permintaan yang sedang diproses</p>
                        </td>
                    </tr>
                `;
                return;
            }

            // *** RENDER DATA ***
            data.forEach((p, index) => {

                let statusBadge =
                    p.status === "pending" ? '<span class="badge pending">⏳ Pending</span>' :
                    p.status === "dikonfirmasi" ? '<span class="badge success">✔ Diterima</span>' :
                    '<span class="badge danger">✘ Ditolak</span>';

                let tanggal = new Date(p.tanggal).toLocaleDateString('id-ID', {
                    day:'2-digit', month:'short', year:'numeric'
                });

                let catatanHTML = '<span style="color:#999;">-</span>';
                if (p.status === "ditolak" && p.catatan && p.catatan !== "_") {
                    let esc = p.catatan.replace(/'/g,"\\'");
                    catatanHTML = `
                        <button class="btn-lihat-catatan" onclick="showCatatan('${esc}')">
                            <i class="fa-solid fa-eye"></i> Lihat Catatan
                        </button>
                    `;
                }

                tbody.innerHTML += `
                    <tr>
                        <td>${index+1}</td>
                        <td>${p.nama_barang}</td>
                        <td>${p.merk_barang}</td>
                        <td>${tanggal}</td>
                        <td>${p.jumlah}</td>
                        <td>${statusBadge}</td>
                        <td>${catatanHTML}</td>
                    </tr>
                `;
            });

        });
}

// load awal + auto refresh
loadStatus();
setInterval(loadStatus, 3000);

</script>
@endsection