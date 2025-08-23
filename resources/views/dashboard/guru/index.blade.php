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
                <th>NIP</th>
                <th>Nama Guru</th>
                <th style="text-align: center;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($guru as $g)
            <tr>
                <td>{{ $g->nip }}</td>
                <td>{{ $g->nama_guru }}</td>
                <td style="text-align: center;">
                    <a href="{{ route('admin.guru.edit', $g->id_guru) }}" class="action-btn edit" title="Edit">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <form action="{{ route('admin.guru.destroy', $g->id_guru) }}" method="POST" style="display:inline;" class="form-delete">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="action-btn delete" title="Hapus">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="3" style="text-align:center;">Belum ada guru</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

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
