@extends('layouts.admin-shell')
@section('title', 'Riwayat Permintaan')

@section('content')
<div class="history-container">
    <div class="history-header">
        <h2><i class="fa-solid fa-clock-rotate-left"></i> Riwayat Permintaan Barang</h2>
        <!-- <a href="{{ route('permintaan.history.download') }}" class="btn-download-pdf">
            <i class="fa-solid fa-file-pdf"></i> Download PDF
        </a> -->
    </div>

    <!-- Summary Statistics - Dipindah ke atas -->
    <div class="summary-stats">
        <div class="stat-card success">
            <i class="fa-solid fa-check-circle"></i>
            <div>
                <h3 id="total-diterima">{{ $riwayatPermintaan->where('status', 'dikonfirmasi')->count() }}</h3>
                <p>Total Diterima</p>
            </div>
        </div>
        <div class="stat-card danger">
            <i class="fa-solid fa-times-circle"></i>
            <div>
                <h3 id="total-ditolak">{{ $riwayatPermintaan->where('status', 'ditolak')->count() }}</h3>
                <p>Total Ditolak</p>
            </div>
        </div>
        <div class="stat-card info">
            <i class="fa-solid fa-list"></i>
            <div>
                <h3 id="total-semua">{{ $riwayatPermintaan->count() }}</h3>
                <p>Total Riwayat</p>
            </div>
        </div>
    </div>

    <!-- Filter Status -->
    <div class="filter-section">
        <label>Filter Status:</label>
        <select id="filter-status" class="filter-select">
            <option value="all">Semua Status</option>
            <option value="dikonfirmasi">Diterima</option>
            <option value="ditolak">Ditolak</option>
        </select>
    </div>

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
// Filter berdasarkan status
document.getElementById('filter-status').addEventListener('change', function() {
    const filterValue = this.value;
    const rows = document.querySelectorAll('#table-body tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const status = row.getAttribute('data-status');
        
        if (filterValue === 'all' || status === filterValue) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    // Update nomor urut
    let counter = 1;
    rows.forEach(row => {
        if (row.style.display !== 'none') {
            row.querySelector('td:first-child').textContent = counter++;
        }
    });
});

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

// SweetAlert untuk download PDF
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false
    });
@endif
</script>

<style>
.history-container {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-top: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.history-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.history-header h2 {
    font-size: 22px;
    font-weight: bold;
    color: #333;
    display: flex;
    align-items: center;
    gap: 10px;
}

.btn-download-pdf {
    background: #dc3545;
    color: #fff;
    padding: 10px 20px;
    border-radius: 8px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.3s;
}

.btn-download-pdf:hover {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(220, 53, 69, 0.3);
}

/* Summary Statistics - Dipindah ke atas sebelum filter */
.summary-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    padding: 20px;
    border-radius: 12px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 15px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
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

.stat-card i {
    font-size: 40px;
    opacity: 0.9;
}

.stat-card h3 {
    font-size: 32px;
    font-weight: bold;
    margin: 0;
}

.stat-card p {
    margin: 0;
    font-size: 14px;
    opacity: 0.9;
}

.filter-section {
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.filter-section label {
    font-weight: 500;
    color: #555;
}

.filter-select {
    padding: 8px 15px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    cursor: pointer;
    transition: border-color 0.3s;
}

.filter-select:focus {
    outline: none;
    border-color: #4CAF50;
}

.table-wrapper {
    overflow-x: auto;
    margin-bottom: 25px;
}

.history-table {
    width: 100%;
    border-collapse: collapse;
}

.history-table th,
.history-table td {
    padding: 12px;
    text-align: center;
    border-bottom: 1px solid #eee;
}

.history-table th {
    background: #0080ffff;
    font-weight: bold;
    color: #ffffffff;
    font-size: 14px;
}

.history-table tbody tr {
    transition: background 0.2s;
}

.history-table tbody tr:hover {
    background: #f8f9fa;
}

.badge {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
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

/* Responsive */
@media (max-width: 768px) {
    .history-header {
        flex-direction: column;
        gap: 15px;
        align-items: flex-start;
    }
    
    .btn-download-pdf {
        width: 100%;
        justify-content: center;
    }
    
    .summary-stats {
        grid-template-columns: 1fr;
    }
    
    .modal-content {
        width: 95%;
        margin: 0 10px;
    }
}
</style>
@endsection