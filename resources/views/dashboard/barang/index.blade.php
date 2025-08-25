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

<!-- 🧾 Tombol Unduh -->
<a href="{{ route('admin.barang.downloadPdf') }}" class="btn-download" style="margin-bottom: 12px; display: inline-block;">
  <i class="fas fa-file-pdf"></i> Unduh Laporan PDF
</a>


<div class="table-container">
<table class="table-dashboard" id="barangTable">
    <thead>
        <tr>
            <th>Nama Barang</th>
            <th>Merk</th>
            <th>Tanggal Pembelian</th>
            <th>Asal Usul</th>
            <th>Harga</th>
            <th>Stok</th>
            <th style="text-align: center;">Aksi</th>
        </tr>
    </thead>
    <tbody>
        @forelse($barang as $b)
        <tr>
            <td>{{ $b->nama_barang }}</td>
            <td>{{ $b->merk_barang }}</td>
            <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d-m-Y') }}</td>
            <td>{{ $b->asal_usul }}</td>
            <td>Rp {{ number_format($b->harga_barang,0,',','.') }}</td>
            <td>{{ $b->stok }}</td>
            <td style="text-align: center;">
                @if(session('auth_role') === 'admin')
                    <a href="{{ route('admin.barang.edit', $b->id_barang) }}" class="action-btn edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>

                    <form action="{{ route('admin.barang.destroy', $b->id_barang) }}" method="POST" style="display:inline;" class="form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                @elseif(session('auth_role') === 'guru')
                    <a href="{{ route('guru.permintaan.fromBarang', $b->id_barang) }}" 
                       class="btn-ajukan">
                       <i class="fas fa-paper-plane"></i> Ajukan
                    </a>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="7" style="text-align: center;">Belum ada barang.</td>
        </tr>
        @endforelse
    </tbody>
</table>
</div>

<script>
// SweetAlert konfirmasi hapus
document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Yakin Hapus?',
            text: "Data ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                this.submit();
            }
        });
    });
});

// Search filter
document.getElementById('searchInput').addEventListener('keyup', function() {
    let filter = this.value.toLowerCase();
    let rows = document.querySelectorAll('#barangTable tbody tr');

    rows.forEach(row => {
        let text = row.innerText.toLowerCase();
        row.style.display = text.includes(filter) ? '' : 'none';
    });
});
</script>

<style>
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

.search-container i {
    color: var(--muted);
    font-size: 14px;
    margin-right: 8px;
}

.search-container input {
    flex: 1;
    border: none;
    outline: none;
    font-size: 14px;
    background: transparent;
}

.search-container:focus-within {
    border-color: var(--accent);
    box-shadow: 0 0 4px rgba(124,58,237,0.4);
}

.table-container {
    margin-top: 10px;
    overflow-x: auto;
}

.table-dashboard {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

.table-dashboard th, .table-dashboard td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

.table-dashboard th {
    background: #f8f9fa;
    text-align: left;
    font-weight: bold;
}

.table-dashboard tr:hover {
    background-color: #f1f1f1;
}

.action-btn {
    background: none;
    border: none;
    cursor: pointer;
    padding: 6px;
    font-size: 16px;
}

.action-btn.edit {
    color: #28a745;
}
.action-btn.edit:hover {
    color: #218838;
}
.action-btn.delete {
    color: #dc3545;
}
.action-btn.delete:hover {
    color: #b52b37;
}

.btn-ajukan {
    background: #4f46e5;
    color: white;
    padding: 6px 10px;
    border-radius: 6px;
    font-size: 13px;
    text-decoration: none;
    transition: 0.2s;
}
.btn-ajukan:hover {
    background: #4338ca;
}

.toolbar {
    display: flex;
    justify-content: flex-end; /* tombol geser ke kanan */
    margin-bottom: 12px;
}

.btn-download {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #dc2626; /* merah PDF */
    color: white;
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 14px;
    text-decoration: none;
    transition: background 0.2s ease;
}
.btn-download:hover {
    background: #b91c1c;
}

</style>
@endsection
