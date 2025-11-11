@extends('layouts.guru-shell')
@section('title', 'Diproses')
@section('content')
<div class="panel">
    <h2>Status Permintaan Barang</h2>
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
            @foreach($permintaan as $index => $p)
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
            @endforeach
        </tbody>
    </table>
    <div class="form-actions">
        <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('guru.home') }}'">Kembali</button>
    </div>
</div>

<!-- Modal Catatan -->
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

<style>
.badge {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}
.badge.pending { 
    background: #fff3cd; 
    color: #856404; 
}
.badge.success { 
    background: #d4edda; 
    color: #155724; 
}
.badge.danger { 
    background: #f8d7da; 
    color: #721c24; 
}

/* Button Lihat Catatan */
.btn-lihat-catatan {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    transition: all 0.3s;
}

.btn-lihat-catatan:hover {
    background: #c82333;
    transform: scale(1.05);
}

.btn-secondary {
    background: #ff0000ff;
    color: #fff;
}

.btn-secondary:hover {
    background: #ff0000ff;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

/* Modal Catatan */
.modal-catatan {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.modal-content {
    background-color: #fff;
    border-radius: 12px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s;
}

@keyframes slideIn {
    from {
        transform: translateY(-50px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #eee;
}

.modal-header h3 {
    margin: 0;
    font-size: 18px;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-close {
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #999;
    transition: color 0.3s;
}

.btn-close:hover {
    color: #dc3545;
}

.modal-body {
    padding: 20px;
}

.modal-body p {
    margin: 0;
    color: #555;
    line-height: 1.6;
    font-size: 14px;
    word-wrap: break-word;
}

.table th:last-child,
.table td:last-child {
    max-width: 250px;
    word-wrap: break-word;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-content {
        width: 95%;
        margin: 0 10px;
    }
}
</style>
@endsection

@section('scripts')
<script>
// Fungsi untuk menampilkan modal catatan
function showCatatan(catatan) {
    document.getElementById('catatan-text').textContent = catatan;
    document.getElementById('modal-catatan').style.display = 'flex';
}

// Fungsi untuk menutup modal
function closeCatatan() {
    document.getElementById('modal-catatan').style.display = 'none';
}

// Tutup modal jika klik di luar konten
window.onclick = function(event) {
    const modal = document.getElementById('modal-catatan');
    if (event.target == modal) {
        modal.style.display = 'none';
    }
}

function loadStatus() {
    fetch("{{ route('guru.permintaan.status') }}", { cache: "no-store" })
        .then(res => res.json())
        .then(data => {
            let tbody = document.querySelector("#tabel-permintaan tbody");
            tbody.innerHTML = "";
            
            data.forEach((p, index) => {
                let statusBadge = "";
                if (p.status === "pending") {
                    statusBadge = '<span class="badge pending">⏳ Pending</span>';
                } else if (p.status === "dikonfirmasi") {
                    statusBadge = '<span class="badge success">✔ Diterima</span>';
                } else if (p.status === "ditolak") {
                    statusBadge = '<span class="badge danger">✘ Ditolak</span>';
                }

                // Format tanggal
                let tanggal = new Date(p.tanggal).toLocaleDateString('id-ID', {
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric'
                });

                // Format catatan dengan button modal
                let catatanHTML = '<span style="color: #999;">-</span>';
                if (p.status === 'ditolak' && p.catatan && p.catatan !== '_' && p.catatan.trim() !== '') {
                    // Escape quotes untuk onclick
                    let catatanEscaped = p.catatan.replace(/'/g, "\\'").replace(/"/g, "&quot;");
                    catatanHTML = `<button class="btn-lihat-catatan" onclick="showCatatan('${catatanEscaped}')">
                        <i class="fa-solid fa-eye"></i> Lihat Catatan
                    </button>`;
                }
                
                tbody.innerHTML += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${p.nama_barang}</td>
                        <td>${p.merk_barang}</td>
                        <td>${tanggal}</td>
                        <td>${p.jumlah}</td>
                        <td>${statusBadge}</td>
                        <td>${catatanHTML}</td>
                    </tr>
                `;
            });
        })
        .catch(err => console.error("Status error:", err));
}

// Load pertama kali
loadStatus();

// Auto-refresh setiap 3 detik
setInterval(loadStatus, 3000);
</script>
@endsection