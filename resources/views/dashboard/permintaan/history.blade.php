@extends('layouts.admin-shell')
@section('title', 'Riwayat Permintaan')

@section('content')
<div class="history-container">
    {{-- Header --}}
    <div class="history-header">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Permintaan Barang</h2>
    </div>

    {{-- Summary Cards --}}
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

    {{-- Filter --}}
    <div class="filter-section">
        <div class="filter-left">
            <label>Filter Status:</label>
            <select id="filter-status" class="filter-select">
                <option value="all">Semua Status</option>
                <option value="dikonfirmasi">Diterima</option>
                <option value="ditolak">Ditolak</option>
            </select>
        </div>
        <a href="{{ route('permintaan.history.download') }}" class="btn-download">
            <i class="fas fa-file-pdf"></i> Unduh PDF
        </a>
    </div>

    {{-- Table --}}
    <div class="table-container">
        <div class="table-wrapper">
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
                                <span class="badge badge-success">✔ Diterima</span>
                            @else
                                <span class="badge badge-danger">✘ Ditolak</span>
                            @endif
                        </td>
                        <td>
                            @if($item->status == 'ditolak' && $item->catatan && $item->catatan != '_')
                                <button class="btn-catatan" onclick="showCatatan('{{ addslashes($item->catatan) }}')">
                                    <i class="fa-solid fa-eye"></i> Lihat
                                </button>
                            @else
                                <span style="color: #999;">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" style="text-align: center; padding: 40px; color: #999;">
                            <i class="fa-solid fa-inbox" style="font-size: 48px; display: block; margin-bottom: 10px; opacity: 0.3;"></i>
                            Belum ada riwayat permintaan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal --}}
<div id="modal-catatan" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-head">
            <h3><i class="fa-solid fa-comment-dots"></i> Catatan Penolakan</h3>
            <button class="modal-close" onclick="closeCatatan()">&times;</button>
        </div>
        <div class="modal-content">
            <p id="catatan-text"></p>
        </div>
    </div>
</div>

<style>
/* Reset & Base */
* { box-sizing: border-box; }

/* Container */
.history-container {
    background: #fff;
    border-radius: 12px;
    padding: 24px;
    margin: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Header */
.history-header {
    margin-bottom: 24px;
}

.history-header h2 {
    font-size: 24px;
    font-weight: 700;
    color: #2c3e50;
    margin: 0;
}

.history-header h2 i {
    margin-right: 8px;
    color: #3498db;
}

/* Summary Stats */
.summary-stats {
    display: flex;
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    flex: 1;
    min-width: 160px;
    padding: 20px;
    border-radius: 10px;
    color: white;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.stat-card i {
    font-size: 32px;
}

.stat-card h3 {
    margin: 0;
    font-size: 28px;
    font-weight: 700;
}

.stat-card p {
    margin: 4px 0 0;
    font-size: 14px;
    opacity: 0.95;
}

.stat-card.success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
}

.stat-card.danger {
    background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
}

.stat-card.info {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
}

/* Filter Section */
.filter-section {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    gap: 16px;
}

.filter-left {
    display: flex;
    align-items: center;
    gap: 12px;
}

.filter-left label {
    font-weight: 600;
    color: #555;
    font-size: 14px;
}

.filter-select {
    padding: 8px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    background: white;
    cursor: pointer;
}

.filter-select:focus {
    outline: none;
    border-color: #3498db;
}

.btn-download {
    padding: 8px 16px;
    background: #e74c3c;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.3s;
}

.btn-download:hover {
    background: #c0392b;
}

/* Table Container */
.table-container {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
}

.table-wrapper {
    overflow-x: auto;
    max-height: 500px;
    overflow-y: auto;
}

/* Table */
.history-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 14px;
}

.history-table thead {
    background: #3498db;
    position: sticky;
    top: 0;
    z-index: 10;
}

.history-table th {
    padding: 14px 12px;
    text-align: center;
    color: white;
    font-weight: 600;
    white-space: nowrap;
}

.history-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #f0f0f0;
}

.history-table tbody tr:hover {
    background-color: #f8f9fa;
}

/* Badges */
.badge {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 13px;
    font-weight: 600;
    white-space: nowrap;
}

.badge-success {
    background: #d4edda;
    color: #155724;
}

.badge-danger {
    background: #f8d7da;
    color: #721c24;
}

/* Button Catatan */
.btn-catatan {
    background: #e74c3c;
    color: white;
    border: none;
    padding: 6px 12px;
    border-radius: 5px;
    font-size: 12px;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: background 0.3s;
}

.btn-catatan:hover {
    background: #c0392b;
}

/* Modal */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.6);
    align-items: center;
    justify-content: center;
    z-index: 9999;
}

.modal-overlay.active {
    display: flex;
}

.modal-box {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.modal-head {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #eee;
}

.modal-head h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #2c3e50;
}

.modal-head i {
    margin-right: 8px;
    color: #e74c3c;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #999;
    cursor: pointer;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 4px;
}

.modal-close:hover {
    background: #f8d7da;
    color: #e74c3c;
}

.modal-content {
    padding: 24px;
}

.modal-content p {
    margin: 0;
    color: #555;
    line-height: 1.6;
}

/* Scrollbar */
.table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.table-wrapper::-webkit-scrollbar-thumb {
    background: #3498db;
    border-radius: 4px;
}

.table-wrapper::-webkit-scrollbar-track {
    background: #f1f1f1;
}

/* Responsive */
@media (max-width: 768px) {
    .history-container {
        padding: 16px;
        margin: 10px;
    }

    .summary-stats {
        flex-direction: column;
    }

    .filter-section {
        flex-direction: column;
        align-items: stretch;
    }

    .filter-left {
        flex-direction: column;
        align-items: stretch;
    }

    .btn-download {
        justify-content: center;
    }

    .history-table th,
    .history-table td {
        padding: 10px 8px;
        font-size: 13px;
    }
}
</style>

<script>
// Filter
document.getElementById('filter-status').addEventListener('change', function() {
    const val = this.value;
    const rows = document.querySelectorAll('#table-body tr[data-status]');
    let no = 1;
    
    rows.forEach(row => {
        const status = row.dataset.status;
        if (val === 'all' || val === status) {
            row.style.display = '';
            row.cells[0].textContent = no++;
        } else {
            row.style.display = 'none';
        }
    });
});

// Modal
function showCatatan(text) {
    document.getElementById('catatan-text').textContent = text;
    document.getElementById('modal-catatan').classList.add('active');
}

function closeCatatan() {
    document.getElementById('modal-catatan').classList.remove('active');
}

// Close on backdrop click
document.getElementById('modal-catatan').addEventListener('click', function(e) {
    if (e.target === this) {
        closeCatatan();
    }
});

// Close on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeCatatan();
    }
});
</script>
@endsection