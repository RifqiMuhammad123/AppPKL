@extends('layouts.admin-shell')
@section('title','Permintaan Barang')

@section('content')
<div class="permintaan-container">
    <div class="header-flex">
    <button class="btn-back" onclick="window.location='{{ route('admin.dashboard') }}'">
        <i class="fa-solid fa-arrow-left"></i>
    </button>
    <h2>Status Permintaan Barang</h2>
</div>

    <!-- TABLE WRAPPER -->
    <div class="permintaan-table-wrapper">
        <table class="permintaan-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Guru</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Tanggal</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($permintaan as $p)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $p->guru->nama_guru ?? '-' }}</td>
                    <td>{{ $p->nama_barang }}</td>
                    <td>{{ $p->merk_barang }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                    <td>{{ $p->jumlah }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span class="badge pending">⏳ Pending</span>
                        @elseif($p->status == 'dikonfirmasi')
                            <span class="badge success">✔ Diterima</span>
                        @else
                            <span class="badge danger">✘ Ditolak</span>
                        @endif
                    </td>
                    <td>
                        @if($p->status == 'pending')
                            <div class="action-buttons">
                                <form action="{{ route('admin.permintaan.konfirmasi', $p->id_permintaan) }}" method="POST" class="form-konfirmasi">
                                    @csrf
                                    <button type="submit" class="btn confirm">
                                        <i class="fa-solid fa-check"></i> <span>Konfirmasi</span>
                                    </button>
                                </form>
                                <form action="{{ route('admin.permintaan.tolak', $p->id_permintaan) }}" method="POST" class="form-tolak">
                                    @csrf
                                    <input type="hidden" name="catatan" class="catatan-input">
                                    <button type="submit" class="btn reject">
                                        <i class="fa-solid fa-times"></i> <span>Tolak</span>
                                    </button>
                                </form>
                            </div>
                        @else
                            <span style="color:#9ca3af; font-size:13px;">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <i class="fa-solid fa-inbox"></i>
                        <p>Belum ada permintaan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<style>
/* === CONTAINER === */
.permintaan-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 30px;
    margin-top: 20px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border: 1px solid #e5e7eb;
}

/* === TITLE === */
.permintaan-container h2 {
    margin-bottom: 28px;
    font-size: 28px;
    font-weight: 700;
    color: #1a1a1a;
}

/* === TABLE WRAPPER === */
.permintaan-table-wrapper {
    max-height: 60vh;       /* scroll muncul di dalam area ini */
    overflow-y: auto;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* === TABLE === */
.permintaan-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
}

/* === HEADER === */
.permintaan-table thead th {
    position: sticky;
    top: 0;
    z-index: 5;
    background: #0055cc;
    color: #fff;
    text-align: center;
    font-weight: 600;
    padding: 14px 16px;
    font-size: 13px;
    border-bottom: 2px solid #0055cc;
}

/* === BODY === */
.permintaan-table tbody td {
    padding: 14px 16px;
    border-bottom: 1px solid #e5e7eb;
    font-size: 14px;
    text-align: center;
    color: #374151;
}
.permintaan-table tbody tr:hover {
    background-color: #f0f7ff;
}

/* === BADGES === */
.badge {
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
}
.badge.pending { background: #fef3c7; color: #92400e; }
.badge.success { background: #dcfce7; color: #166534; }
.badge.danger { background: #fee2e2; color: #991b1b; }

/* === BUTTONS === */
.action-buttons { display: flex; gap: 8px; justify-content: center; }
.btn {
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    border: none;
    transition: all 0.3s ease;
}
.btn.confirm {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #fff;
}
.btn.reject {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    color: #fff;
}
.btn.confirm:hover { transform: translateY(-2px); }
.btn.reject:hover { transform: translateY(-2px); }

/* BACK BUTTON MODERN */
.header-flex {
    display: flex;
    align-items: center;    /* Biar icon & judul segaris tengah */
    gap: 14px;              /* Jarak modern */
    margin-bottom: 25px;    /* Jarak dari content */
}

/* Judul */
.header-flex .title-text {
    font-size: 26px;
    font-weight: 700;
    color: #121212;
    margin: 0;             /* Hilangkan margin default h2 */
}

/* Tombol Back */
.btn-back {
    background: #f44336;
    color: #fff;
    border: none;
    width: 42px;
    height: 42px;
    border-radius: 50%;
    font-size: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    transition: 0.25s ease;
}

.btn-back:hover {
    background: #c62828;
    transform: translateY(-2px);
    box-shadow: 0 4px 10px rgba(229,57,53,0.35);
}


/* === EMPTY STATE === */
.permintaan-table tbody tr td[colspan] {
    padding: 50px 16px;
    text-align: center;
    color: #9ca3af;
}
.permintaan-table tbody tr td[colspan] i {
    font-size: 48px;
    margin-bottom: 10px;
    opacity: 0.5;
}

/* === CUSTOM SCROLLBAR === */
.permintaan-table-wrapper::-webkit-scrollbar { width: 8px; }
.permintaan-table-wrapper::-webkit-scrollbar-thumb {
    background: #bcd2ff;
    border-radius: 6px;
}
.permintaan-table-wrapper::-webkit-scrollbar-thumb:hover {
    background: #5ea0ff;
}

/* === RESPONSIVE === */
@media (max-width: 768px) {
    .permintaan-container { padding: 20px; }
    .permintaan-table th, .permintaan-table td { font-size: 12px; padding: 10px 8px; }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.querySelectorAll('.form-konfirmasi').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Konfirmasi Permintaan?',
            text: "Barang akan diterima!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#22c55e',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Konfirmasi',
            cancelButtonText: 'Batal'
        }).then(result => { if (result.isConfirmed) this.submit(); });
    });
});

document.querySelectorAll('.form-tolak').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const formElement = this;
        Swal.fire({
            title: 'Tolak Permintaan',
            input: 'textarea',
            inputPlaceholder: 'Tuliskan alasan penolakan...',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Tolak',
            cancelButtonText: 'Batal',
            preConfirm: alasan => {
                if (!alasan) {
                    Swal.showValidationMessage('⚠️ Alasan penolakan harus diisi!');
                    return false;
                }
                return alasan;
            }
        }).then(result => {
            if (result.isConfirmed) {
                formElement.querySelector('.catatan-input').value = result.value;
                formElement.submit();
            }
        });
    });
});
</script>
@endsection