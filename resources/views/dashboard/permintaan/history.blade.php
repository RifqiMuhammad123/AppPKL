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

    {{-- ✅ Filter Form (Bulan, Tahun, Status) --}}
    <form method="GET" action="{{ route('admin.permintaan.history') }}" id="filter-form">
        <div class="filter-section">
            <div class="filter-left">
                <div class="filter-group">
                    <label>Bulan:</label>
                    <select name="bulan" class="filter-select">
                        <option value="">Semua Bulan</option>
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="filter-group">
                    <label>Tahun:</label>
                    <select name="tahun" class="filter-select">
                        <option value="">Semua Tahun</option>
                        @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="filter-group">
                    <label>Status:</label>
                    <select name="status" class="filter-select">
                        <option value="">Semua Status</option>
                        <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Diterima</option>
                        <option value="ditolak" {{ request('status') == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="filter-buttons">
                    <button type="submit" class="btn-filter">
                        <i class="fa-solid fa-filter"></i> Filter
                    </button>
                    <a href="{{ route('admin.permintaan.history') }}" class="btn-reset">
                        <i class="fa-solid fa-rotate-right"></i> Reset
                    </a>
                </div>
            </div>

            <a href="{{ route('permintaan.history.download', ['bulan' => request('bulan'), 'tahun' => request('tahun'), 'status' => request('status')]) }}" class="btn-download">
                <i class="fas fa-file-pdf"></i> Unduh PDF
            </a>
        </div>
    </form>

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

/* ✅ Filter Section - Enhanced */
#filter-form {
    margin-bottom: 20px;
}

.filter-section {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    gap: 16px;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 10px;
}

.filter-left {
    display: flex;
    align-items: flex-end;
    gap: 12px;
    flex-wrap: wrap;
    flex: 1;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    font-weight: 600;
    color: #555;
    font-size: 13px;
}

.filter-select {
    padding: 8px 16px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    background: white;
    cursor: pointer;
    min-width: 140px;
    transition: all 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: #3498db;
    box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
}

.filter-buttons {
    display: flex;
    gap: 8px;
}

.btn-filter,
.btn-reset {
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: all 0.3s;
    text-decoration: none;
    white-space: nowrap;
}

.btn-filter {
    background: #27ae60;
    color: white;
}

.btn-filter:hover {
    background: #229954;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(39, 174, 96, 0.3);
}

.btn-reset {
    background: #95a5a6;
    color: white;
}

.btn-reset:hover {
    background: #7f8c8d;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(149, 165, 166, 0.3);
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
    transition: all 0.3s;
    white-space: nowrap;
}

.btn-download:hover {
    background: #c0392b;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(231, 76, 60, 0.3);
}

/* Table Container */
.table-container {
    border: 1px solid #e0e0e0;
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
    border-collapse: separate;
    border-spacing: 0;
    font-size: 14px;
}

.history-table thead {
    background: #0080ff;
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
    border-right: 1px solid #d0e3f5; 
}

.history-table th:last-child {
    border-right: none;
}


.history-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #d0e3f5;
    border-right: 1px solid #d0e3f5;
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

    .filter-group {
        width: 100%;
    }

    .filter-select {
        width: 100%;
        min-width: auto;
    }

    .filter-buttons {
        width: 100%;
    }

    .btn-filter,
    .btn-reset {
        flex: 1;
        justify-content: center;
    }

    .btn-download {
        width: 100%;
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