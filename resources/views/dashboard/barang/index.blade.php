@extends('layouts.admin-shell')
@section('title','Daftar Barang')

@section('content')
<h2>Daftar Barang</h2>

@if(session('success'))
<script>
Swal.fire({
    icon: 'success',
    title: 'Berhasil!',
    text: '{{ session("success") }}',
    timer: 2000,
    showConfirmButton: false
});
</script>
@endif

<!-- TOOLBAR: Search + Download Button -->
<div class="toolbar">
    <div class="search-container">
        <i class="fas fa-search"></i>
        <input type="text" id="searchInput" placeholder="Cari barang..." />
    </div>

    <a href="{{ route('barang.download.tcpdf') }}" class="btn-download">
      <i class="fas fa-file-pdf"></i> Unduh PDF
    </a>
</div>

<!-- TABLE DENGAN SCROLL -->
<div class="table-container">
    <table class="table-dashboard" id="barangTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Tgl Beli</th>
                <th>Harga</th>
                <th>Stok</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                    @if($b->foto)
                        <img src="{{ asset('storage/'.$b->foto) }}" 
                            alt="Foto {{ $b->nama_barang }}">
                    @else
                        <span>-</span>
                    @endif
                </td>
                <td>{{ $b->nama_barang }}</td>
                <td>{{ $b->merk_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d-m-Y') }}</td>
                <td>Rp {{ number_format($b->harga_barang,0,',','.') }}</td>
                <td>{{ $b->stok }}</td>
                <td style="text-align: center;">
                    @if(session('auth_role') === 'admin')
                        <a href="{{ route('admin.barang.edit', $b->id_barang) }}" class="btn-icon-text edit">
                            <i class="fas fa-pencil-alt"></i> <span>Edit</span>
                        </a>

                        <form action="{{ route('admin.barang.destroy', $b->id_barang) }}" method="POST" style="display:inline;" class="form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon-text delete">
                                <i class="fas fa-trash"></i> <span>Hapus</span>
                            </button>
                        </form>
                    @elseif(session('auth_role') === 'guru')
                        <a href="{{ route('guru.permintaan.fromBarang', $b->id_barang) }}" class="btn-ajukan">
                            <i class="fas fa-paper-plane"></i> <span>Ajukan</span>
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; padding: 40px 16px; color: #9ca3af;">Belum ada barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
// SweetAlert konfirmasi hapus
document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e){
        e.preventDefault();
        let formDelete = this;
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data ini akan dihapus permanen!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, hapus!",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                formDelete.submit();
            }
        });
    });
});

// Search filter
document.getElementById('searchInput').addEventListener('keyup', function(){
    let filter = this.value.toLowerCase();
    document.querySelectorAll('#barangTable tbody tr').forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

@endsection

<style>
/* ========================================
   HEADER & TITLE
   ======================================== */
h2 {
  font-size: 28px;
  font-weight: 700;
  color: #1a1a1a;
  margin-bottom: 20px;
  letter-spacing: -0.5px;
}

/* ========================================
   TOOLBAR (Search + Download)
   ======================================== */
.toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
  gap: 15px;
  flex-wrap: nowrap;
}

/* ========================================
   SEARCH CONTAINER
   ======================================== */
.search-container {
  display: flex;
  align-items: center;
  background: #ffffff;
  border: 2px solid #e5e7eb;
  border-radius: 10px;
  padding: 10px 14px;
  flex: 1;
  max-width: 400px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
  transition: all 0.3s ease;
}

.search-container:focus-within {
  border-color: #1565c0;
  box-shadow: 0 4px 12px rgba(21, 101, 192, 0.15);
}

.search-container i {
  color: #9ca3af;
  margin-right: 10px;
  font-size: 16px;
}

.search-container input {
  flex: 1;
  border: none;
  outline: none;
  background: transparent;
  font-size: 14px;
  color: #333;
}

.search-container input::placeholder {
  color: #d1d5db;
}

/* ========================================
   DOWNLOAD BUTTON
   ======================================== */
.btn-download {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
  color: #fff;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.3s ease;
  box-shadow: 0 2px 8px rgba(220, 38, 38, 0.3);
  white-space: nowrap;
  flex-shrink: 0;
}

.btn-download:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(220, 38, 38, 0.4);
}

.btn-download i {
  font-size: 16px;
}

/* ========================================
   TABLE CONTAINER WITH SCROLL
   ======================================== */
.table-container {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
  border: 1px solid #e5e7eb;
  overflow: auto;
  max-height: 600px;
}

/* ========================================
   TABLE BASE
   ======================================== */
.table-dashboard {
  width: 100%;
  border-collapse: collapse;
  background: #ffffff;
}

/* ========================================
   TABLE HEADER
   ======================================== */
.table-dashboard thead {
  background: linear-gradient(135deg, #1565c0 0%, #0d47a1 100%);
  position: sticky;
  top: 0;
  z-index: 10;
}

.table-dashboard th {
  color: #ffffff;
  font-weight: 600;
  text-align: left;
  padding: 14px 16px;
  font-size: 13px;
  letter-spacing: 0.3px;
  text-transform: uppercase;
  border-bottom: 2px solid #1555b0;
}

/* ========================================
   TABLE BODY
   ======================================== */
.table-dashboard td {
  padding: 14px 16px;
  border-bottom: 1px solid #e5e7eb;
  font-size: 14px;
  color: #374151;
  vertical-align: middle;
}

.table-dashboard tbody tr {
  transition: all 0.2s ease;
}

.table-dashboard tbody tr:hover {
  background-color: #f0f7ff;
}

/* ========================================
   TABLE CELLS STYLING
   ======================================== */
.table-dashboard td:nth-child(2) {
  text-align: center;
}

.table-dashboard td:nth-child(8) {
  text-align: center;
}

/* Foto styling */
.table-dashboard img {
  max-width: 80px;
  max-height: 80px;
  object-fit: cover;
  border-radius: 6px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  transition: transform 0.2s ease;
}

.table-dashboard img:hover {
  transform: scale(1.05);
}

/* ========================================
   ACTION BUTTONS
   ======================================== */
.btn-icon-text {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 7px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  transition: all 0.2s ease;
  border: none;
  white-space: nowrap;
}

.btn-icon-text i {
  font-size: 14px;
}

/* Edit Button */
.btn-icon-text.edit {
  background: linear-gradient(135deg, #42a5f5 0%, #1976d2 100%);
  color: #fff;
  box-shadow: 0 2px 6px rgba(66, 165, 245, 0.3);
}

.btn-icon-text.edit:hover {
  background: linear-gradient(135deg, #1976d2 0%, #1565c0 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 10px rgba(66, 165, 245, 0.4);
}

/* Delete Button */
.btn-icon-text.delete {
  background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
  color: #fff;
  box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3);
}

.btn-icon-text.delete:hover {
  background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 10px rgba(239, 68, 68, 0.4);
}

/* Ajukan Button */
.btn-ajukan {
  background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
  color: #fff;
  padding: 7px 12px;
  border-radius: 6px;
  font-size: 13px;
  font-weight: 600;
  text-decoration: none;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  transition: all 0.2s ease;
  box-shadow: 0 2px 6px rgba(79, 70, 229, 0.3);
  border: none;
  white-space: nowrap;
}

.btn-ajukan:hover {
  background: linear-gradient(135deg, #4338ca 0%, #3730a3 100%);
  transform: translateY(-1px);
  box-shadow: 0 4px 10px rgba(79, 70, 229, 0.4);
}

.btn-ajukan i {
  font-size: 14px;
}

/* ========================================
   EMPTY STATE
   ======================================== */
.table-dashboard tbody tr td[colspan] {
  padding: 40px 16px;
  text-align: center;
  color: #9ca3af;
  font-weight: 500;
}

/* ========================================
   SCROLLBAR STYLING
   ======================================== */
.table-container::-webkit-scrollbar {
  width: 8px;
  height: 8px;
}

.table-container::-webkit-scrollbar-track {
  background: #f3f4f6;
}

.table-container::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.table-container::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* ========================================
   RESPONSIVE
   ======================================== */
@media (max-width: 768px) {
  h2 {
    font-size: 24px;
    margin-bottom: 18px;
  }

  .toolbar {
    flex-wrap: wrap;
    justify-content: flex-start;
  }

  .search-container {
    max-width: 100%;
    min-width: 0;
  }

  .btn-download {
    width: 100%;
    justify-content: center;
  }

  .table-container {
    max-height: 500px;
  }

  .table-dashboard th,
  .table-dashboard td {
    padding: 10px 12px;
    font-size: 12px;
  }

  .table-dashboard img {
    max-width: 60px;
    max-height: 60px;
  }

  .btn-icon-text,
  .btn-ajukan {
    padding: 6px 10px;
    font-size: 11px;
  }

  .btn-icon-text span,
  .btn-ajukan span {
    display: none;
  }
}
</style>