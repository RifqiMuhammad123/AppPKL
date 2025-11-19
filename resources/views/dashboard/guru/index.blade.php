@extends('layouts.admin-shell')
@section('title','Daftar Guru')
<link rel="stylesheet" href="{{ asset('css/form.css') }}">
@section('content')
<h2>Daftar Guru</h2>
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
<div class="table-container">
    <table class="table-dashboard">
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Guru</th>
                <th>Password</th>
                <th>Foto</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guru as $g)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $g->nip }}</td>
                <td>{{ $g->nama_guru }}</td>
                <td>{{ $g->password_plain }}</td>
                <td>
                     @if($g->foto && $g->foto !== 'icon.jpg')
                        <img src="{{ asset('img/' . $g->foto) }}" alt="Foto Guru" 
                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                    @else
                        <img src="{{ asset('img/icon.jpg') }}" alt="Default" 
                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                    @endif
                </td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.guru.edit', $g->id_guru) }}" class="action-btn edit btn-icon-text" title="Edit">
                        <i class="fas fa-pencil-alt"></i> <span>Edit</span>
                    </a>
                    <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" class="form-delete" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete btn-icon-text" title="Hapus">
                            <i class="fas fa-trash"></i> <span>Hapus</span>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;">Belum ada guru</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
<style>
/* Sticky header table */
.table-container {
    max-height: 600px; /* Sesuaikan tinggi maksimal */
    overflow-y: auto;
    position: relative;
}

.table-dashboard {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.table-dashboard thead {
    position: sticky;
    top: 0;
    z-index: 10;
    background-color: #f5f5f5; /* Sesuaikan dengan warna header table Anda */
}

.table-dashboard thead th {
    background: #0080ff; 
    padding: 12px;
    border-right: 1px solid #d0e3f5;
}

.table-dashboard tbody td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

/* Style tombol icon + text */
.btn-icon-text {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 13px;
    text-decoration: none;
    cursor: pointer;
}
.btn-icon-text.edit {
    background-color: #42a5f5;
    color: #fff;
}
.btn-icon-text.edit:hover {
    background-color: #0a90feff;
}
.btn-icon-text.delete {
    background-color: #ef5350;
    color: #fff;
    border: none;
}
.btn-icon-text.delete:hover {
    background-color: #e53935;
}
</style>
<script>
document.querySelectorAll('.form-delete').forEach(form => {
    form.addEventListener('submit', function(e){
        e.preventDefault();
        let formDelete = this;
        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Data guru akan dihapus!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Ya, Hapus",
            cancelButtonText: "Batal"
        }).then((result) => {
            if (result.isConfirmed) {
                formDelete.submit();
            }
        });
    });
});
</script>
@endsection