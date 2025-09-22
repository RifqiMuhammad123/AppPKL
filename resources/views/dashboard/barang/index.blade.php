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

<!-- Search Input -->
<div class="search-container">
    <i class="fas fa-search"></i>
    <input type="text" id="searchInput" placeholder="Cari barang..." />
</div>

<!-- Tombol Unduh PDF -->
<a href="{{ route('barang.download.tcpdf') }}" class="btn-download">
  <i class="fas fa-file-pdf"></i> Unduh Laporan PDF
</a>

<div class="table-container">
    <table class="table-dashboard" id="barangTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Tanggal Pembelian</th>
                <th>Harga</th>
                <th>Stok</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($barang as $b)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td style="width:120px; text-align:center;">
                    @if($b->foto)
                        <img src="{{ asset('storage/'.$b->foto) }}" 
                            alt="Foto {{ $b->nama_barang }}" 
                            style="max-width:100px; max-height:100px; object-fit:cover; border-radius:5px;">
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
                        <a href="{{ route('admin.barang.edit', $b->id_barang) }}" class="action-btn edit btn-icon-text">
                            <i class="fas fa-pencil-alt"></i> <span>Edit</span>
                        </a>

                        <form action="{{ route('admin.barang.destroy', $b->id_barang) }}" method="POST" style="display:inline;" class="form-delete">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn delete btn-icon-text">
                                <i class="fas fa-trash"></i> <span>Hapus</span>
                            </button>
                        </form>
                    @elseif(session('auth_role') === 'guru')
                        <a href="{{ route('guru.permintaan.fromBarang', $b->id_barang) }}" class="btn-ajukan btn-icon-text">
                            <i class="fas fa-paper-plane"></i> <span>Ajukan</span>
                        </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Belum ada barang.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
/* Search Input */
.search-container {
    margin: 10px 0 15px;
    display: flex;
    align-items: center;
    background: white;
    border: 1px solid #ccc;
    border-radius: 8px;
    padding: 6px 10px;
    width: 280px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.08);
}
.search-container i { color: #7b7193; margin-right: 8px; }
.search-container input { flex: 1; border: none; outline: none; background: transparent; }
.search-container:focus-within { border-color: #1e88e5; box-shadow: 0 0 4px rgba(30,136,229,0.4); }

/* Table */
.table-container { margin-top: 10px; overflow-x: auto; }
.table-dashboard { width: 100%; border-collapse: collapse; background: white; border-radius: 8px; overflow: hidden; }
.table-dashboard th, .table-dashboard td { padding: 12px; border-bottom: 1px solid #ddd; }
.table-dashboard th { background: #f8f9fa; font-weight: bold; text-align: left; }
.table-dashboard tr:hover { background-color: #f1f1f1; }

/* Tombol icon + text */
.btn-icon-text { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 6px; font-size: 14px; text-decoration: none; cursor: pointer; transition: 0.2s; }
.btn-icon-text.edit { background: #42a5f5; color: #fff; }
.btn-icon-text.edit:hover { background: #0a90fe; }
.btn-icon-text.delete { background: #dc3545; color: #fff; border: none; }
.btn-icon-text.delete:hover { background: #b52b37; }
.btn-icon-text i { font-size: 14px; }

/* Tombol ajukan */
.btn-ajukan { background: #4f46e5; color: #fff; padding: 6px 10px; border-radius: 6px; font-size: 13px; text-decoration: none; transition: 0.2s; }
.btn-ajukan:hover { background: #4338ca; }

/* Tombol download PDF */
.btn-download { display: inline-flex; align-items: center; gap: 6px; background: #dc2626; color: #fff; padding: 8px 14px; border-radius: 6px; font-size: 14px; text-decoration: none; transition: 0.2s; margin-bottom: 12px; }
.btn-download:hover { background: #b91c1c; }
</style>

<script>
// SweetAlert konfirmasi hapus (sama dengan yang di Guru)
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
