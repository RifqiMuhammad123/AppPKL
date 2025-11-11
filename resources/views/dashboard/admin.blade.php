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

    <!-- Card Stok Rendah (Clickable untuk Modal) -->
    <div class="card card-warning" onclick="openStokRendahModal()" style="cursor: pointer;">         
        <div class="card-icon card-icon-warning">
            <i class="fa-solid fa-triangle-exclamation"></i>
        </div>         
        <div class="card-meta">             
            <h4>Stok Rendah</h4>             
            <p>Klik untuk detail</p>             
            <div class="stat">{{ $barangStokRendah->count() }}</div>         
        </div>     
    </div>
</div>  

<!-- Single Table: Barang Terbaru -->
<div class="panel">
    <h4><i class="fa-solid fa-box"></i> Barang Terbaru</h4>

    <div class="table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th class="hide-mobile">Merk</th>
                    <th class="hide-mobile">Tgl Beli</th>
                    <th class="hide-tablet">Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangTerbaru as $index => $b)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        @if($b->foto)
                        <img src="{{ asset('storage/' . $b->foto) }}" alt="{{ $b->nama_barang }}" class="product-img">
                        @else
                        <div class="no-image">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $b->nama_barang }}</strong>
                        <div class="mobile-info">
                            <span class="hide-desktop">{{ $b->merk_barang }}</span>
                            <span class="hide-desktop">{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</span>
                        </div>
                    </td>
                    <td class="hide-mobile">{{ $b->merk_barang }}</td>
                    <td class="hide-mobile">{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</td>
                    <td class="hide-tablet"><strong>Rp {{ number_format($b->harga_barang,0,',','.') }}</strong></td>
                    <td>
                        <span class="badge badge-info">{{ $b->stok }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-data">
                        <i class="fa-solid fa-inbox"></i>
                        <span>Belum ada data barang terbaru.</span>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Stok Rendah -->
<div id="modalStokRendah" class="modal">
    <div class="modal-content modal-stok-rendah">
        <button class="modal-close" onclick="closeStokRendahModal()">&times;</button>
        
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Daftar Barang Stok Rendah (Segera Restock!)
            </h3>
        </div>

        <div class="modal-body">
            @if($barangStokRendah->count() > 0)
                <div class="stok-rendah-list">
                    @foreach($barangStokRendah as $barang)
                    <div class="stok-rendah-item">
                        <div class="stok-item-icon">
                            <i class="fa-solid fa-box-open"></i>
                        </div>
                        <div class="stok-item-info">
                            <h5>{{ $barang->nama_barang }}</h5>
                            <p class="merk">{{ $barang->merk_barang }}</p>
                            <div class="stok-details">
                                <span class="stok-current">
                                    <i class="fa-solid fa-cubes"></i>
                                    Stok: <strong>{{ $barang->stok }}</strong>
                                </span>
                                <span class="stok-min">
                                    <i class="fa-solid fa-chart-line"></i>
                                    Min: <strong>{{ $barang->stok_minimum ?? 10 }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="stok-item-badge">
                            <span class="badge-critical">MAU HABIS</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fa-solid fa-circle-check"></i>
                    <h4>Semua Stok Aman!</h4>
                    <p>Tidak ada barang dengan stok rendah saat ini.</p>
                </div>
            @endif
        </div>

        <div class="modal-footer">
            <button class="btn btn-primary" onclick="closeStokRendahModal()">
                <i class="fa-solid fa-check"></i> Kembali
            </button>
        </div>
    </div>
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
    
    loadNotif();     
    setInterval(loadNotif, 3000);

    // Modal Functions
    function openStokRendahModal() {
        document.getElementById('modalStokRendah').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeStokRendahModal() {
        document.getElementById('modalStokRendah').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('modalStokRendah');
        if (event.target == modal) {
            closeStokRendahModal();
        }
    }

    // Close modal with ESC key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeStokRendahModal();
        }
    });
</script>  

<style> 
    /* ========================================
       BASE STYLES
       ======================================== */
    * {
        box-sizing: border-box;
    }

    /* Notif Badge */
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
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.4);
        min-width: 20px;
        text-align: center;
    }
    
    .card-icon {     
        position: relative; 
    }

    /* ========================================
       CARDS GRID - RESPONSIVE
       ======================================== */
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    /* Card Warning Style */
    .card-warning {
        border-left: 4px solid #ff0000ff;
        transition: all 0.3s ease;
    }

    .card-warning:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 30px rgba(255, 152, 0, 0.35) !important;
    }

    .card-icon-warning {
        background: linear-gradient(135deg, #ff0000ff, #ff5722) !important;
    }

    /* ========================================
       PANEL - RESPONSIVE HEIGHT
       ======================================== */
    .panel {
        height: auto;
        min-height: 450px;
        max-height: calc(100vh - 350px);
    }

    .panel h4 {
        margin-bottom: 16px;
    }

    .panel h4 i {
        margin-right: 8px;
        color: var(--accent);
    }

    /* ========================================
       TABLE - RESPONSIVE
       ======================================== */
    .table-scroll {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .table {
        width: 100%;
        min-width: 600px;
    }

    .product-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #e3f2fd;
    }

    /* Mobile Info (hidden on desktop) */
    .mobile-info {
        display: none;
        flex-direction: column;
        gap: 4px;
        margin-top: 4px;
        font-size: 12px;
        color: #666;
    }

    /* Empty Data State */
    .empty-data {
        text-align: center;
        padding: 40px;
        color: #999;
    }

    .empty-data i {
        font-size: 48px;
        margin-bottom: 10px;
        display: block;
    }

    /* Badge Styles */
    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        white-space: nowrap;
    }

    .badge-info {
        background: linear-gradient(135deg, #42a5f5, #1e88e5);
        color: white;
        box-shadow: 0 2px 8px rgba(30, 136, 229, 0.3);
    }

    /* No Image Placeholder */
    .no-image {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #1976d2;
        font-size: 20px;
    }

    /* ========================================
       MODAL - RESPONSIVE
       ======================================== */
    .modal-stok-rendah {
        max-width: 600px;
        width: 90%;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        margin: 20px;
    }

    .modal-body {
        padding: 0;
        max-height: 500px;
        overflow-y: auto;
    }

    .modal-body::-webkit-scrollbar {
        width: 8px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #ff0000ff;
        border-radius: 10px;
    }

    .modal-body::-webkit-scrollbar-thumb:hover {
        background: #f50000ff;
    }

    .stok-rendah-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
        padding: 16px;
    }

    .stok-rendah-item {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 16px;
        background: #fff3e0;
        border: 2px solid #ffe0b2;
        border-left: 4px solid #ff0000ff;
        border-radius: 12px;
        transition: all 0.3s ease;
    }

    .stok-rendah-item:hover {
        background: #ffe0b2;
        transform: translateX(4px);
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.2);
    }

    .stok-item-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #ff9800, #ff5722);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        flex-shrink: 0;
        box-shadow: 0 4px 12px rgba(255, 152, 0, 0.3);
    }

    .stok-item-info {
        flex: 1;
        min-width: 0;
    }

    .stok-item-info h5 {
        margin: 0 0 4px 0;
        font-size: 16px;
        font-weight: 700;
        color: #333;
        word-wrap: break-word;
    }

    .stok-item-info .merk {
        margin: 0 0 8px 0;
        font-size: 13px;
        color: #666;
        word-wrap: break-word;
    }

    .stok-details {
        display: flex;
        gap: 16px;
        font-size: 13px;
        flex-wrap: wrap;
    }

    .stok-details span {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #555;
    }

    .stok-details i {
        color: #ff9800;
    }

    .stok-details strong {
        color: #d84315;
        font-weight: 700;
    }

    .stok-item-badge {
        flex-shrink: 0;
    }

    .badge-critical {
        display: inline-block;
        padding: 6px 12px;
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.4);
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #4caf50;
    }

    .empty-state i {
        font-size: 64px;
        margin-bottom: 16px;
        display: block;
    }

    .empty-state h4 {
        margin: 0 0 8px 0;
        font-size: 20px;
        color: #2e7d32;
    }

    .empty-state p {
        margin: 0;
        color: #66bb6a;
        font-size: 14px;
    }

    .modal-footer {
        padding: 16px;
        border-top: 1px solid #eee;
        display: flex;
        justify-content: flex-end;
    }

    /* ========================================
       RESPONSIVE BREAKPOINTS
       ======================================== */
    
    /* Large Desktop (> 1400px) */
    @media (min-width: 1400px) {
        .cards {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Desktop (1200px - 1399px) */
    @media (min-width: 1200px) and (max-width: 1399px) {
        .cards {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    /* Tablet Landscape (992px - 1199px) */
    @media (min-width: 992px) and (max-width: 1199px) {
        .cards {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    /* Tablet Portrait (768px - 991px) */
    @media (min-width: 768px) and (max-width: 991px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 14px;
        }

        .panel {
            min-height: 400px;
            max-height: calc(100vh - 320px);
        }

        .hide-tablet {
            display: none !important;
        }

        .table {
            min-width: 500px;
        }
    }

    /* Mobile Large (576px - 767px) */
    @media (min-width: 576px) and (max-width: 767px) {
        .cards {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .card {
            padding: 16px;
        }

        .card .stat {
            font-size: 22px;
        }

        .card-meta h4 {
            font-size: 14px;
        }

        .card-meta p {
            font-size: 12px;
        }

        .panel {
            min-height: 350px;
            max-height: calc(100vh - 300px);
        }

        .hide-mobile {
            display: none !important;
        }

        .hide-tablet {
            display: none !important;
        }

        .mobile-info {
            display: flex;
        }

        .hide-desktop {
            display: block;
        }

        .table {
            min-width: 400px;
        }

        .product-img,
        .no-image {
            width: 40px;
            height: 40px;
        }

        .stok-rendah-item {
            padding: 12px;
            gap: 12px;
        }

        .stok-item-icon {
            width: 40px;
            height: 40px;
            font-size: 20px;
        }

        .modal-stok-rendah {
            width: 95%;
        }
    }

    /* Mobile Small (< 576px) */
    @media (max-width: 575px) {
        .cards {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .card {
            padding: 14px;
        }

        .card .stat {
            font-size: 20px;
        }

        .card-meta h4 {
            font-size: 13px;
        }

        .card-meta p {
            font-size: 11px;
        }

        .card-icon {
            width: 50px;
            height: 50px;
        }

        .card-icon i {
            font-size: 22px;
        }

        .panel {
            min-height: 300px;
            max-height: calc(100vh - 280px);
        }

        .panel h4 {
            font-size: 16px;
        }

        .hide-mobile {
            display: none !important;
        }

        .hide-tablet {
            display: none !important;
        }

        .mobile-info {
            display: flex;
        }

        .hide-desktop {
            display: block;
        }

        .table {
            min-width: 350px;
            font-size: 13px;
        }

        .table th,
        .table td {
            padding: 8px 6px;
        }

        .product-img,
        .no-image {
            width: 35px;
            height: 35px;
        }

        .badge {
            font-size: 11px;
            padding: 3px 8px;
        }

        /* Modal Adjustments */
        .modal-stok-rendah {
            width: 95%;
            max-width: 100%;
            margin: 10px;
        }

        .modal-header h3 {
            font-size: 16px;
        }

        .stok-rendah-item {
            flex-direction: column;
            text-align: center;
            padding: 12px;
        }

        .stok-item-icon {
            width: 45px;
            height: 45px;
            font-size: 20px;
        }

        .stok-item-info h5 {
            font-size: 14px;
        }

        .stok-details {
            justify-content: center;
            font-size: 12px;
        }

        .stok-item-badge {
            margin-top: 8px;
        }

        .badge-critical {
            font-size: 10px;
            padding: 5px 10px;
        }

        .modal-footer {
            padding: 12px;
        }

        .btn {
            width: 100%;
            justify-content: center;
        }

        .notif-badge {
            font-size: 10px;
            padding: 2px 5px;
            min-width: 18px;
        }
    }

    /* Extra Small Mobile (< 400px) */
    @media (max-width: 399px) {
        .cards {
            gap: 10px;
        }

        .card {
            padding: 12px;
        }

        .card .stat {
            font-size: 18px;
        }

        .card-meta h4 {
            font-size: 12px;
        }

        .card-meta p {
            font-size: 10px;
        }

        .table {
            min-width: 320px;
            font-size: 12px;
        }

        .product-img,
        .no-image {
            width: 30px;
            height: 30px;
        }

        .no-image i {
            font-size: 14px;
        }
    }

    /* ========================================
       UTILITY CLASSES
       ======================================== */
    .hide-desktop {
        display: none;
    }

    @media (max-width: 767px) {
        .hide-mobile {
            display: none !important;
        }
        
        .hide-desktop {
            display: block;
        }
    }

    @media (max-width: 991px) {
        .hide-tablet {
            display: none !important;
        }
    }

    /* ========================================
       PRINT STYLES
       ======================================== */
    @media print {
        .card-clickable,
        .card-warning,
        .modal {
            display: none !important;
        }

        .panel {
            height: auto;
            max-height: none;
        }

        .table-scroll {
            overflow: visible;
        }
    }
</style> 
@endsection