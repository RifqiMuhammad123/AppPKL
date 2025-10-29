@extends('layouts.admin-shell')
@section('title', 'Riwayat Permintaan')

@section('content')
<div class="history-container">
    <div class="history-header">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Permintaan Barang</h2>
    </div>

    <!-- Summary Cards -->
    <div class="summary-stats">
        <div class="stat-card success">
            <i class="fa-solid fa-check-circle"></i>
            <div>
                <h3>{{ $riwayatPermintaan->where('status', 'dikonfirmasi')->count() }}</h3>
                <p>Total Diterima</p>
            </div>
        </div>
        <div class="stat-card danger">
            <i class="fa-solid fa-times-circle"></i>
            <div>
                <h3>{{ $riwayatPermintaan->where('status', 'ditolak')->count() }}</h3>
                <p>Total Ditolak</p>
            </div>
        </div>
        <div class="stat-card info">
            <i class="fa-solid fa-list"></i>
            <div>
                <h3>{{ $riwayatPermintaan->count() }}</h3>
                <p>Total Riwayat</p>
            </div>
        </div>
    </div>

    <!-- Filter -->
    <div class="filter-section">
        <label>Filter Status:</label>
        <select id="filter-status" class="filter-select">
            <option value="all">Semua Status</option>
            <option value="dikonfirmasi">Diterima</option>
            <option value="ditolak">Ditolak</option>
        </select>
    </div>

    <!-- Table Section -->
    <div class="table-container">
        <div class="table-inner-scroll">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Guru</th>
                        <th>Nama Barang</th>
                        <th>Merk</th>
                        <th>Tanggal</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody id="table-body">
                    @forelse($riwayatPermintaan as $index => $item)
                    <tr data-status="{{ $item->status }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->nama_guru }}</td>
                        <td>{{ $item->nama_barang }}</td>
                        <td>{{ $item->merk_barang }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                        <td>{{ $item->jumlah }}</td>
                        <td>
                            @if($item->status == 'dikonfirmasi')
                                <span class="badge success">✔ Diterima</span>
                            @else
                                <span class="badge danger">✘ Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'ditolak' && $item->catatan && $item->catatan != '_')
                                <button class="btn-lihat-catatan" onclick="showCatatan('{{ addslashes($item->catatan) }}')">
                                    <i class="fa-solid fa-eye"></i> Lihat Catatan
                                </button>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; color: #999;">
                            <i class="fa-solid fa-inbox"></i> Belum ada riwayat permintaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
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

<script>
document.getElementById('filter-status').addEventListener('change', function() {
    const val = this.value;
    const rows = document.querySelectorAll('#table-body tr');
    let counter = 1;
    rows.forEach(row => {
        const status = row.dataset.status;
        if (val === 'all' || val === status) {
            row.style.display = '';
            row.children[0].textContent = counter++;
        } else {
            row.style.display = 'none';
        }
    });
});

function showCatatan(catatan) {
    document.getElementById('catatan-text').textContent = catatan;
    document.getElementById('modal-catatan').style.display = 'flex';
}
function closeCatatan() {
    document.getElementById('modal-catatan').style.display = 'none';
}
window.onclick = e => {
    const m = document.getElementById('modal-catatan');
    if (e.target === m) m.style.display = 'none';
};
</script>

<style>
/* === FIX LAYOUT OVERFLOW (PENTING) === */
.main {
    overflow: visible !important;
    height: auto !important;
    min-height: 100vh !important;
}

/* ====== WRAPPER ====== */
.history-container {
    background: #fff;
    border-radius: 14px;
    padding: 25px;
    margin-bottom: 35px; 
    box-shadow: 0 2px 10px rgba(0,0,0,0.08);
}

/* ====== HEADER ====== */
.history-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: -10px;
}
.history-header h2 {
    font-size: 22px;
    font-weight: bold;
    color: #333;
}

/* ====== SUMMARY ====== */
.summary-stats {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
    flex-wrap: wrap;
}
.stat-card {
    flex: 1;
    min-width: 150px;
    padding: 12px 15px;
    border-radius: 12px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 12px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
}
.stat-card i { font-size: 26px; }
.stat-card h3 { margin: 0; font-size: 22px; font-weight: bold; }
.stat-card p { font-size: 13px; margin: 0; opacity: 0.9; }
.stat-card.success { background: linear-gradient(135deg,#11998e,#38ef7d); }
.stat-card.danger { background: linear-gradient(135deg,#eb3349,#f45c43); }
.stat-card.info { background: linear-gradient(135deg,#4facfe,#00f2fe); }

/* ====== FILTER ====== */
.filter-section {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}
.filter-select {
    padding: 6px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
}

/* ====== TABLE ====== */
.table-container {
    border-radius: 10px;
    border: 1px solid #eee;
    background: #fff;
    overflow: hidden;
    box-shadow: inset 0 0 5px rgba(0,0,0,0.05);
}

/* area scroll */
.table-inner-scroll {
    max-height: calc(100vh - 400px); /* menyesuaikan tinggi layar */
    overflow-y: auto;
    overflow-x: hidden;
}

/* tabel utama */
.history-table {
    width: 100%;
    border-collapse: collapse;
}
.history-table th, .history-table td {
    padding: 10px 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
    white-space: nowrap;
}
.history-table thead th {
    position: sticky;
    top: 0;
    background: #0080ff;
    color: white;
    font-size: 14px;
    z-index: 3;
}
.history-table tbody tr:hover {
    background: #f9fafc;
}

/* ====== BADGES ====== */
.badge {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
}
.badge.success { background: #d4edda; color: #155724; }
.badge.danger { background: #f8d7da; color: #721c24; }

/* ====== BUTTON CATATAN ====== */
.btn-lihat-catatan {
    background: #dc3545;
    color: white;
    padding: 6px 10px;
    border: none;
    border-radius: 5px;
    font-size: 12px;
    cursor: pointer;
}
.btn-lihat-catatan:hover { background: #c82333; }

/* ====== MODAL ====== */
.modal-catatan {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    align-items: center;
    justify-content: center;
    z-index: 999;
}
.modal-content {
    background: white;
    border-radius: 10px;
    padding: 20px;
    max-width: 500px;
    width: 90%;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
    padding-bottom: 10px;
}
.btn-close {
    background: none;
    border: none;
    font-size: 26px;
    color: #777;
}
.btn-close:hover { color: #dc3545; }

/* ====== SCROLLBAR ====== */
.table-inner-scroll::-webkit-scrollbar {
    width: 8px;
}
.table-inner-scroll::-webkit-scrollbar-thumb {
    background: #0080ff;
    border-radius: 10px;
}
.table-inner-scroll::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* ====== RESPONSIVE ====== */
@media (max-width: 768px) {
    .summary-stats { flex-direction: column; }
    .stat-card { justify-content: flex-start; }
}
</style>
@endsection
