@extends('layouts.guru-shell')
@section('title', 'Dashboard Guru')

@section('content')
<style>
/* CRITICAL: Paksa hilangkan scroll dari semua parent */
body.dash {
    overflow: hidden !important;
    height: 100vh !important;
}

.content {
    overflow: hidden !important;
    height: 100vh !important;
}

.main {
    overflow: visible !important;
    height: calc(100vh - 70px) !important;
    padding: 20px;
    background: #f5f7fa;
}

/* Override semua styling yang konflik */
.guru-dashboard-wrapper {
    padding: 0;
    max-width: 100%;
    width: 100%;
    overflow: visible !important;
    height: 100% !important;
}

/* Dashboard Cards - 3 kolom penuh tanpa gap kosong */
.guru-dashboard-wrapper .dashboard-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.guru-dashboard-wrapper .dashboard-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    cursor: default;
    display: flex;
    align-items: center;
    gap: 16px;
}

.guru-dashboard-wrapper .dashboard-card.clickable {
    cursor: pointer;
}

.guru-dashboard-wrapper .dashboard-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.12);
}

/* Card Icon - Dengan warna berbeda */
.guru-dashboard-wrapper .dashboard-card-icon {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 24px;
    flex-shrink: 0;
}

/* Warna icon berbeda untuk setiap card */
.guru-dashboard-wrapper .dashboard-card:nth-child(1) .dashboard-card-icon {
    background: linear-gradient(135deg, #002fffff 0%, #00c3ffff 100%);
}

.guru-dashboard-wrapper .dashboard-card:nth-child(2) .dashboard-card-icon {
    background: linear-gradient(135deg, #002fffff 0%, #00c3ffff 100%);
}

.guru-dashboard-wrapper .dashboard-card:nth-child(3) .dashboard-card-icon {
    background: linear-gradient(135deg, #002fffff 0%, #00c3ffff 100%);
}

.guru-dashboard-wrapper .dashboard-card:nth-child(4) .dashboard-card-icon {
    background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
}

.guru-dashboard-wrapper .dashboard-card-meta {
    flex: 1;
}

.guru-dashboard-wrapper .dashboard-card-meta h4 {
    margin: 0 0 4px 0;
    font-size: 14px;
    font-weight: 600;
    color: #333;
    line-height: 1.3;
}

.guru-dashboard-wrapper .dashboard-card-meta p {
    margin: 0 0 8px 0;
    font-size: 12px;
    color: #888;
    line-height: 1.3;
}

.guru-dashboard-wrapper .dashboard-card-stat {
    font-size: 28px;
    font-weight: 700;
    color: #2c3e50;
}

/* Barang Panel - Sama dengan Admin */
.guru-dashboard-wrapper .barang-panel {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: visible !important;
    height: auto !important;
}

.guru-dashboard-wrapper .barang-panel-header {
    padding: 20px 24px;
    border-bottom: 1px solid #e8ecef;
    background: white;
    display: flex;
    align-items: center;
    gap: 12px;
}

.guru-dashboard-wrapper .barang-panel-header i {
    font-size: 20px;
    color: #2563eb;
}

.guru-dashboard-wrapper .barang-panel-header h2 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1e293b;
    flex: 1;
}

.guru-dashboard-wrapper .barang-panel-header p {
    margin: 0;
    font-size: 12px;
    color: #64748b;
}

/* Table dengan scroll */
.guru-dashboard-wrapper .barang-table-wrapper {
    overflow-x: auto;
    overflow-y: auto;
    max-height: calc(100vh - 340px) !important;
    position: relative;
}

.guru-dashboard-wrapper .barang-table-wrapper::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

.guru-dashboard-wrapper .barang-table-wrapper::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.guru-dashboard-wrapper .barang-table-wrapper::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

.guru-dashboard-wrapper .barang-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}

/* Table - Sama dengan Admin */
.guru-dashboard-wrapper .data-table {
    width: 100%;
    min-width: 800px;
    border-collapse: collapse;
}

.guru-dashboard-wrapper .data-table thead {
    position: sticky;
    top: 0;
    background: #2563eb;
    z-index: 10;
}

.guru-dashboard-wrapper .data-table th {
    padding: 16px;
    text-align: left;
    font-size: 13px;
    font-weight: 600;
    color: white;
    text-transform: capitalize;
    border: none;
}

.guru-dashboard-wrapper .data-table td {
    padding: 16px;
    border-bottom: 1px solid #f1f5f9;
    font-size: 14px;
    color: #334155;
}

.guru-dashboard-wrapper .data-table tbody tr {
    transition: background 0.15s ease;
    cursor: pointer;
}

.guru-dashboard-wrapper .data-table tbody tr:hover {
    background: #f8fafc;
}

.guru-dashboard-wrapper .table-center {
    text-align: center !important;
}

/* Product Image - Sama dengan Admin */
.guru-dashboard-wrapper .item-img {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
}

.guru-dashboard-wrapper .item-no-img {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, #e0e7ff, #dbeafe);
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    color: #2563eb;
    font-size: 20px;
}

/* Mobile Info */
.guru-dashboard-wrapper .item-mobile-info {
    display: none;
    flex-direction: column;
    gap: 4px;
    margin-top: 6px;
    font-size: 12px;
    color: #64748b;
}

/* Badge - Sama dengan Admin (rounded badge) */
.guru-dashboard-wrapper .stock-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    font-size: 13px;
    font-weight: 600;
    color: white;
    background: #2563eb;
}

.guru-dashboard-wrapper .stock-badge.high {
    background: #2563eb;
}

.guru-dashboard-wrapper .stock-badge.low {
    background: #ff0000ff;
}

/* Empty State */
.guru-dashboard-wrapper .table-empty {
    text-align: center;
    padding: 60px 20px !important;
    color: #94a3b8;
}

.guru-dashboard-wrapper .table-empty i {
    display: block;
    font-size: 64px;
    margin-bottom: 16px;
    color: #e2e8f0;
}

.guru-dashboard-wrapper .table-empty span {
    font-size: 16px;
}

/* Responsive Classes */
.guru-dashboard-wrapper .hide-on-tablet {
    display: table-cell;
}

.guru-dashboard-wrapper .hide-on-mobile {
    display: table-cell;
}

/* Tablet */
@media (max-width: 991px) {
    .guru-dashboard-wrapper .hide-on-tablet {
        display: none !important;
    }
    
    .guru-dashboard-wrapper .dashboard-cards {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .guru-dashboard-wrapper .barang-table-wrapper {
        max-height: 450px;
    }
    
    .guru-dashboard-wrapper .data-table {
        min-width: 600px;
    }
}

/* Mobile */
@media (max-width: 767px) {
    .main {
        padding: 12px;
    }
    
    .guru-dashboard-wrapper .hide-on-mobile {
        display: none !important;
    }
    
    .guru-dashboard-wrapper .item-mobile-info {
        display: flex;
    }
    
    .guru-dashboard-wrapper .dashboard-cards {
        grid-template-columns: 1fr;
        gap: 12px;
        margin-bottom: 16px;
    }
    
    .guru-dashboard-wrapper .dashboard-card {
        padding: 16px;
    }
    
    .guru-dashboard-wrapper .dashboard-card-icon {
        width: 50px;
        height: 50px;
        font-size: 20px;
    }
    
    .guru-dashboard-wrapper .dashboard-card-stat {
        font-size: 24px;
    }
    
    .guru-dashboard-wrapper .barang-panel-header {
        padding: 16px;
    }
    
    .guru-dashboard-wrapper .barang-panel-header h2 {
        font-size: 16px;
    }
    
    .guru-dashboard-wrapper .barang-table-wrapper {
        max-height: 400px;
    }
    
    .guru-dashboard-wrapper .data-table {
        min-width: 500px;
        font-size: 13px;
    }
    
    .guru-dashboard-wrapper .data-table th,
    .guru-dashboard-wrapper .data-table td {
        padding: 12px 10px;
    }
    
    .guru-dashboard-wrapper .item-img,
    .guru-dashboard-wrapper .item-no-img {
        width: 45px;
        height: 45px;
    }
}

/* Small Mobile */
@media (max-width: 480px) {
    .main {
        padding: 10px;
    }
    
    .guru-dashboard-wrapper .dashboard-cards {
        gap: 10px;
    }
    
    .guru-dashboard-wrapper .dashboard-card {
        padding: 14px;
    }
    
    .guru-dashboard-wrapper .dashboard-card-meta h4 {
        font-size: 13px;
    }
    
    .guru-dashboard-wrapper .dashboard-card-meta p {
        font-size: 11px;
    }
    
    .guru-dashboard-wrapper .dashboard-card-stat {
        font-size: 22px;
    }
    
    .guru-dashboard-wrapper .barang-panel-header h2 {
        font-size: 15px;
    }
    
    .guru-dashboard-wrapper .barang-table-wrapper {
        max-height: 350px;
    }
    
    .guru-dashboard-wrapper .data-table {
        min-width: 450px;
    }
    
    .guru-dashboard-wrapper .item-img,
    .guru-dashboard-wrapper .item-no-img {
        width: 40px;
        height: 40px;
    }
}
</style>

<div class="guru-dashboard-wrapper">
    <!-- Cards Section -->
    <div class="dashboard-cards">
        <!-- Card 1 -->
        <div class="dashboard-card">
            <div class="dashboard-card-icon"><i class="fa-solid fa-boxes-stacked"></i></div>
            <div class="dashboard-card-meta">
                <h4>All Stok Barang</h4>
                <p>Semua barang yang tersedia</p>
                <div class="dashboard-card-stat">{{ number_format($allStok) }}</div>
            </div>
        </div>
        
        <!-- Card 2 -->
        <div class="dashboard-card clickable" onclick="window.location.href='{{ route("guru.permintaan.create") }}'">
            <div class="dashboard-card-icon"><i class="fa-solid fa-clipboard-list"></i></div>
            <div class="dashboard-card-meta">
                <h4>Permintaan Barang</h4>
                <p>Ajukan barang ke Admin</p>
            </div>
        </div>
        
        <!-- Card 3 -->
        <div class="dashboard-card clickable" onclick="window.location.href='{{ route("guru.permintaan.proses") }}'">
            <div class="dashboard-card-icon"><i class="fa-solid fa-hourglass-half"></i></div>
            <div class="dashboard-card-meta">
                <h4>Status Permintaan</h4>
                <p>Diproses</p>
            </div>
        </div>
    </div>

    <!-- Daftar Barang Section -->
    <div class="barang-panel">
        <div class="barang-panel-header">
            <h2><i class="fa-solid fa-boxes-stacked"></i> Daftar Barang</h2>
            <p>Klik pada barang untuk membuat permintaan</p>
        </div>
        
        <div class="barang-table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Foto</th>
                        <th>Nama Barang</th>
                        <th class="hide-on-mobile">Merk</th>
                        <th class="hide-on-mobile">Tanggal</th>
                        <th class="hide-on-tablet">Harga</th>
                        <th>Stok</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($barang as $index => $b)
                    <tr class="data-row" onclick="window.location='{{ route('guru.permintaan.fromBarang', $b->id_barang) }}'">
                        <td class="table-center">{{ $index + 1 }}</td>
                        <td class="table-center">
                            @if($b->foto)
                                <img src="{{ asset('storage/'.$b->foto) }}" 
                                    alt="Foto {{ $b->nama_barang }}" 
                                    class="item-img">
                            @else
                                <div class="item-no-img">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $b->nama_barang }}</strong>
                            <div class="item-mobile-info">
                                <span>{{ $b->merk_barang }}</span>
                                <span>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="hide-on-mobile">{{ $b->merk_barang }}</td>
                        <td class="hide-on-mobile">{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</td>
                        <td class="hide-on-tablet"><strong>Rp {{ number_format($b->harga_barang,0,',','.') }}</strong></td>
                        <td class="table-center">
                            <span class="stock-badge {{ $b->stok > 10 ? 'high' : 'low' }}">
                                {{ $b->stok }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="table-empty">
                            <i class="fa-solid fa-inbox"></i>
                            <span>Belum ada barang tersedia.</span>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection