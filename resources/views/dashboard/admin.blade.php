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

    <!-- Card Stok Rendah -->     
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

    <h4><i class="fa-solid fa-box"></i> Barang Terbaru</h4>

    <div class="table-scroll">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Tgl Beli</th>
                    <th>Harga</th>
                    <th>Stok</th>
                </tr>
            </thead>
            <tbody>
                @forelse($barangTerbaru as $index => $b)
                <tr>
                    <td>
                        @if(method_exists($barangTerbaru, 'firstItem'))
                            {{ $barangTerbaru->firstItem() + $index }}
                        @else
                            {{ $index + 1 }}
                        @endif
                    </td>
                    <td>
                        @if($b->foto)
                        <img src="{{ asset('storage/' . $b->foto) }}" 
                             alt="{{ $b->nama_barang }}" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 8px; border: 2px solid #e3f2fd;">
                        @else
                        <div class="no-image">
                            <i class="fa-solid fa-image"></i>
                        </div>
                        @endif
                    </td>
                    <td><strong>{{ $b->nama_barang }}</strong></td>
                    <td>{{ $b->merk_barang }}</td>
                    <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d M Y') }}</td>
                    <td><strong>Rp {{ number_format($b->harga_barang,0,',','.') }}</strong></td>

                    <!-- ===== PERBAIKAN STOK MERAH OTOMATIS ===== -->
                    <td>
                        @php
                            $isLow = $b->stok <= ($b->stok_minimum ?? 10);
                        @endphp

                        <span class="badge {{ $isLow ? 'badge-danger' : 'badge-info' }}">
                            {{ $b->stok }}
                        </span>
                    </td>
                    <!-- ========== END PERBAIKAN ========== -->
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #999;">
                        <i class="fa-solid fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                        Belum ada data barang terbaru.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    <!-- Pagination -->
    @if(method_exists($barangTerbaru, 'hasPages') && $barangTerbaru->hasPages())
    <div class="pagination-wrapper">
        <div class="pagination-info">
            Menampilkan <strong>{{ $barangTerbaru->firstItem() }}</strong> - <strong>{{ $barangTerbaru->lastItem() }}</strong> 
            dari <strong>{{ $barangTerbaru->total() }}</strong> data
        </div>
        
        <ul class="pagination">
            {{-- Previous Page Link --}}
            @if ($barangTerbaru->onFirstPage())
                <li class="disabled">
                    <span><i class="fa-solid fa-chevron-left"></i></span>
                </li>
            @else
                <li class="prev">
                    <a href="{{ $barangTerbaru->previousPageUrl() }}" rel="prev">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($barangTerbaru->links()->elements[0] as $page => $url)
                @if ($page == $barangTerbaru->currentPage())
                    <li class="active"><span>{{ $page }}</span></li>
                @else
                    <li><a href="{{ $url }}">{{ $page }}</a></li>
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($barangTerbaru->hasMorePages())
                <li class="next">
                    <a href="{{ $barangTerbaru->nextPageUrl() }}" rel="next">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </li>
            @else
                <li class="disabled">
                    <span><i class="fa-solid fa-chevron-right"></i></span>
                </li>
            @endif
        </ul>
    </div>
    @endif
</div>

<!-- Modal Stok Rendah -->
<div id="modalStokRendah" class="modal">
    <div class="modal-content modal-stok-rendah">
        <button class="modal-close" onclick="closeStokRendahModal()">&times;</button>
        
        <div class="modal-header">
            <h3 class="modal-title">
                <i class="fa-solid fa-triangle-exclamation"></i>
                Daftar Barang Stok Rendah (Restock Barang Segera!)
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

    function openStokRendahModal() {
        document.getElementById('modalStokRendah').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeStokRendahModal() {
        document.getElementById('modalStokRendah').style.display = 'none';
        document.body.style.overflow = 'auto';
    }

    window.onclick = function(event) {
        const modal = document.getElementById('modalStokRendah');
        if (event.target == modal) {
            closeStokRendahModal();
        }
    }
</script>  

<style> 
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
    }

    .card-icon {     
        position: relative; 
    }

    /* Cards */
    .cards {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    /* Card Warning */
    .card-warning {
        border-left: 4px solid #ffffff;
        transition: all 0.3s ease;
    }

    .card-warning:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 30px rgba(255, 152, 0, 0.35) !important;
    }

    .card-icon-warning {
        background: linear-gradient(135deg, #ff0000ff, #ff5722) !important;
    }

    /* Panel */
    .panel {
        height: calc(100vh - 350px);
        min-height: 450px;
    }

    /* Fix table scroll - pastikan table-scroll bisa scroll */
    .table-scroll {
        max-height: calc(100vh - 450px);
        min-height: 300px;
    }

    /* Badge */
    .badge {
        padding: 4px 10px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
    }

    .badge-info {
        background: linear-gradient(135deg, #42a5f5, #1e88e5);
        color: white;
    }

    /* ===== BADGE MERAH (PERBAIKAN) ===== */
    .badge-danger {
        background: linear-gradient(135deg, #f44336, #d32f2f);
        color: white;
        box-shadow: 0 2px 8px rgba(244, 67, 54, 0.3);
    }
    /* =================================== */

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

    /* Modal Stok Rendah */
    .modal-stok-rendah {
        max-width: 600px;
        width: 90%;
        max-height: 85vh;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #e0e0e0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .modal-header .modal-title {
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #333;
    }

    .modal-body {
        padding: 16px 20px;
        overflow-y: auto;
        flex: 1;
    }

    .stok-rendah-list {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .stok-rendah-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 10px;
        background: #fff7f7;
        box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        gap: 12px;
    }

    .stok-item-icon {
        font-size: 28px;
        color: #f44336;
        flex-shrink: 0;
    }

    .stok-item-info h5 {
        margin: 0;
        font-size: 15px;
        font-weight: 600;
        color: #333;
    }

    .stok-item-info .merk {
        font-size: 13px;
        color: #666;
        margin-bottom: 4px;
    }

    .stok-details {
        display: flex;
        gap: 12px;
        font-size: 12px;
        color: #555;
        align-items: center;
    }

    .stok-details i {
        margin-right: 4px;
        color: #f44336;
    }

    .stok-item-badge .badge-critical {
        background: #ff5722;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 8px;
        border-radius: 8px;
        white-space: nowrap;
    }

    /* Modal Footer */
    .modal-footer {
        padding: 12px 20px;
        border-top: 1px solid #e0e0e0;
        display: flex;
        justify-content: flex-end;
    }

    .modal-footer .btn {
        padding: 6px 14px;
        font-size: 13px;
    }

    /* Scrollbar Styling */
    .modal-body::-webkit-scrollbar {
        width: 6px;
    }

    .modal-body::-webkit-scrollbar-thumb {
        background: #ff9800;
        border-radius: 3px;
    }
</style> 
@endsection