@extends('layouts.admin-shell')
@section('title','Permintaan Barang')

@section('content')
<div class="permintaan-container">
    <h2>Status Permintaan Barang</h2>

    <table class="permintaan-table">
        <thead>
            <tr>
                <th>Nama Guru</th>
                <th>Foto</th>
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
                <td>
                    @if($p->barang && $p->barang->foto)
                        <img src="{{ asset('storage/barang/'.$p->barang->foto) }}" 
                            alt="Foto Barang" 
                            style="width:50px; height:50px; object-fit:cover; border-radius:6px;">
                    @else
                        <span style="color:#888; font-size:13px;">-</span>
                    @endif
                </td>
                <td>{{ $p->nama_barang }}</td>
                <td>{{ $p->merk_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($p->tanggal)->format('d M Y') }}</td>
                <td>{{ $p->jumlah }}</td>
                <td>
                    @if($p->status == 'pending')
                        <span class="badge pending">Pending</span>
                    @elseif($p->status == 'dikonfirmasi')
                        <span class="badge success">✔ Diterima</span>
                    @else
                        <span class="badge danger">✘ Ditolak</span>
                    @endif
                </td>
                <td>
                    @if($p->status == 'pending')
                        <!-- Konfirmasi -->
                        <form action="{{ route('admin.permintaan.konfirmasi', $p->id_permintaan) }}" 
                              method="POST" class="form-konfirmasi" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn confirm">✔ Konfirmasi</button>
                        </form>

                        <!-- Tolak -->
                        <form action="{{ route('admin.permintaan.tolak', $p->id_permintaan) }}" 
                              method="POST" class="form-tolak" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn reject">✘ Tolak</button>
                        </form>
                    @else
                        <span style="color:#888; font-size:13px;">-</span>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align:center;">Belum ada permintaan.</td>
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
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });

    // ❌ Tolak
    document.querySelectorAll('.form-tolak').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Tolak Permintaan?',
                text: "Barang tidak akan diterima!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
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
            timer: 2000,
            showConfirmButton: false
        });
    @endif
</script>

<style>
/* ========================================
   CONTAINER
   ======================================== */
.permintaan-container {
  background: #ffffff;
  border-radius: 12px;
  padding: 20px;
  margin-top: 30px;
  box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
}

/* ========================================
   TITLE
   ======================================== */
.permintaan-container h2 {
  margin-bottom: 18px;
  font-size: 20px;
  font-weight: 700;
  color: #000000ff;
}

/* ========================================
   TABLE BASE
   ======================================== */
.permintaan-table {
  width: 100%;
  border-collapse: separate; /* biar bisa ada border-radius */
  border-spacing: 0 8px;     /* jarak antar baris */
}

/* ========================================
   HEADER (TH)
   ======================================== */
.permintaan-table th {
  background: #1565c0;
  color: #ffffff;
  font-size: 14px;
  font-weight: 600;
  text-align: center;
  padding: 12px;
}

/* Lengkung di pojok atas */
.permintaan-table th:first-child {
  border-top-left-radius: 8px;
}

.permintaan-table th:last-child {
  border-top-right-radius: 8px;
}

/* ========================================
   BODY (TD)
   ======================================== */
.permintaan-table td {
  background: #f6faff;
  padding: 12px;
  text-align: center;
  border-top: 1px solid #e3f2fd;
  border-bottom: 1px solid #e3f2fd;
  vertical-align: middle;
}

/* Lengkung di pojok bawah baris terakhir */
.permintaan-table tr:last-child td:first-child {
  border-bottom-left-radius: 8px;
}

.permintaan-table tr:last-child td:last-child {
  border-bottom-right-radius: 8px;
}

/* ========================================
   HOVER EFFECT
   ======================================== */
.permintaan-table tr:hover td {
  background: #e3f2fd;
  transition: background 0.2s ease;
}


.badge {
    padding: 4px 10px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}
.badge.pending { background: #fff3cd; color: #856404; }
.badge.success { background: #d4edda; color: #155724; }
.badge.danger  { background: #f8d7da; color: #721c24; }

.btn {
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 13px;
    text-decoration: none;
    margin: 2px;
    display: inline-block;
    cursor: pointer;
    border: none;
}
.btn.confirm { background: #28a745; color: #fff; }
.btn.confirm:hover { background: #218838; }
.btn.reject { background: #dc3545; color: #fff; }
.btn.reject:hover { background: #b52b37; }
</style>
@endsection
