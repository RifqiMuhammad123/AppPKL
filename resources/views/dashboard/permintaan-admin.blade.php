@extends('layouts.admin-shell')
@section('title','Permintaan Barang')

@section('content')
<div class="permintaan-container">
    <h2>Status Permintaan Barang</h2>

    <table class="permintaan-table">
        <thead>
            <tr>
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
                            <!-- Konfirmasi -->
                            <form action="{{ route('admin.permintaan.konfirmasi', $p->id_permintaan) }}" 
                                  method="POST" class="form-konfirmasi">
                                @csrf
                                <button type="submit" class="btn confirm">
                                    <i class="fa-solid fa-check"></i> Konfirmasi
                                </button>
                            </form>

                            <!-- Tolak -->
                            <form action="{{ route('admin.permintaan.tolak', $p->id_permintaan) }}" 
                                  method="POST" class="form-tolak">
                                @csrf
                                <input type="hidden" name="catatan" class="catatan-input">
                                <button type="submit" class="btn reject">
                                    <i class="fa-solid fa-times"></i> Tolak
                                </button>
                            </form>
                        </div>
                    @else
                        <span style="color:#888; font-size:13px;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align:center; padding: 40px; color: #999;">
                    <i class="fa-solid fa-inbox" style="font-size: 48px; margin-bottom: 10px; display: block;"></i>
                    <p style="margin: 0;">Belum ada permintaan</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // ✅ Konfirmasi
    document.querySelectorAll('.form-konfirmasi').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Permintaan?',
                text: "Barang akan diterima!",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Konfirmasi',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-custom-title',
                    htmlContainer: 'swal-custom-text'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // ❌ Tolak dengan Input Catatan
    document.querySelectorAll('.form-tolak').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formElement = this;
            
            Swal.fire({
                title: 'Tolak Permintaan',
                html: `
                    <div class="rejection-form">
                        <p class="rejection-label">Alasan Penolakan <span style="color: #dc3545;">*</span></p>
                        <textarea id="alasan-tolak" class="swal2-textarea" 
                                  placeholder="Contoh: Stok tidak tersedia, budget tidak mencukupi, dll..."
                                  rows="4"></textarea>
                        <small class="helper-text">Berikan penjelasan yang jelas kepada guru</small>
                    </div>
                `,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fa-solid fa-times"></i> Ya, Tolak',
                cancelButtonText: 'Batal',
                width: '500px',
                customClass: {
                    popup: 'swal-custom-popup',
                    title: 'swal-custom-title',
                    htmlContainer: 'swal-custom-container'
                },
                didOpen: () => {
                    // Auto focus ke textarea
                    document.getElementById('alasan-tolak').focus();
                },
                preConfirm: () => {
                    const alasan = document.getElementById('alasan-tolak').value.trim();
                    if (!alasan) {
                        Swal.showValidationMessage('⚠️ Alasan penolakan harus diisi!');
                        return false;
                    }
                    if (alasan.length < 10) {
                        Swal.showValidationMessage('⚠️ Alasan minimal 10 karakter!');
                        return false;
                    }
                    return alasan;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    // Masukkan catatan ke hidden input
                    formElement.querySelector('.catatan-input').value = result.value;
                    formElement.submit();
                }
            });
        });
    });

    // ✅ Notifikasi sukses dari session
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            timer: 2500,
            showConfirmButton: false,
            customClass: {
                popup: 'swal-custom-popup'
            }
        });
    @endif
</script>

<style>
/* ========================================
   CONTAINER
   ======================================== */
.permintaan-container {
    background: #fff;
    border-radius: 12px;
    padding: 25px;
    margin-top: 30px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

/* ========================================
   TITLE
   ======================================== */
.permintaan-container h2 {
    margin-bottom: 20px;
    font-size: 22px;
    font-weight: bold;
    color: #333;
}

/* ========================================
   TABLE BASE
   ======================================== */
.permintaan-table {
    width: 100%;
    border-collapse: collapse;
}

.permintaan-table th, 
.permintaan-table td {
    padding: 14px 12px;
    border-bottom: 1px solid #eee;
    text-align: center;
}

.permintaan-table th {
    background: #f8f9fa;
    font-weight: bold;
    color: #333;
    font-size: 14px;
}

.permintaan-table tbody tr {
    transition: background 0.2s;
}

.permintaan-table tbody tr:hover {
    background: #f8f9fa;
}

.badge {
    padding: 5px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    display: inline-block;
}
.badge.pending { background: #fff3cd; color: #856404; }
.badge.success { background: #d4edda; color: #155724; }
.badge.danger  { background: #f8d7da; color: #721c24; }

/* Action Buttons Container */
.action-buttons {
    display: flex;
    gap: 8px;
    justify-content: center;
    align-items: center;
}

.btn {
    padding: 8px 16px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    border: none;
    transition: all 0.3s;
    white-space: nowrap;
}

.btn.confirm { 
    background: #28a745; 
    color: #fff; 
}
.btn.confirm:hover { 
    background: #218838; 
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(40, 167, 69, 0.3);
}

.btn.reject { 
    background: #dc3545; 
    color: #fff; 
}
.btn.reject:hover { 
    background: #c82333; 
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(220, 53, 69, 0.3);
}

/* Custom SweetAlert Styles */
.swal-custom-popup {
    border-radius: 12px !important;
    padding: 20px !important;
}

.swal-custom-title {
    font-size: 22px !important;
    font-weight: 600 !important;
    color: #333 !important;
    margin-bottom: 10px !important;
}

.swal-custom-text {
    font-size: 15px !important;
    color: #666 !important;
}

.swal-custom-container {
    margin: 0 !important;
    padding: 0 !important;
}

/* Rejection Form Styles */
.rejection-form {
    text-align: left;
    padding: 10px 0;
}

.rejection-label {
    font-size: 14px;
    font-weight: 600;
    color: #333;
    margin-bottom: 10px;
    display: block;
}

.swal2-textarea {
    width: 100% !important;
    padding: 12px !important;
    border: 2px solid #ddd !important;
    border-radius: 8px !important;
    font-size: 14px !important;
    font-family: inherit !important;
    resize: vertical !important;
    transition: all 0.3s !important;
    margin-bottom: 8px !important;
}

.swal2-textarea:focus {
    border-color: #dc3545 !important;
    outline: none !important;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1) !important;
}

.helper-text {
    display: block;
    font-size: 12px;
    color: #999;
    font-style: italic;
    margin-top: 5px;
}

.swal2-validation-message {
    background: #f8d7da !important;
    color: #721c24 !important;
    border: 1px solid #f5c6cb !important;
    border-radius: 6px !important;
    padding: 10px !important;
    margin-top: 10px !important;
    font-size: 13px !important;
}

/* Responsive */
@media (max-width: 768px) {
    .action-buttons {
        flex-direction: column;
        gap: 6px;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
        padding: 10px 12px;
    }
    
    .permintaan-table {
        font-size: 13px;
    }
    
    .permintaan-table th,
    .permintaan-table td {
        padding: 10px 8px;
    }
}

@media (max-width: 480px) {
    .swal-custom-popup {
        width: 95% !important;
        padding: 15px !important;
    }
}
</style>
@endsection