@extends('layouts.admin-shell')
@section('title','Tambah Guru')

<link rel="stylesheet" href="{{ asset('css/form.css') }}">

@section('content')
<div class="form-dashboard">
    <h2 style="text-align:center;">Tambah Guru</h2>
    <form id="form-tambah-guru" action="{{ route('admin.guru.store') }}" method="POST">
        @csrf
        <label for="nip">NIP</label>
        <input type="text" name="nip" id="nip" maxlength="18" required
       oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0,18)">

        <label for="nama_guru">Nama Guru</label>
        <input type="text" name="nama_guru" id="nama_guru" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn-submit">Tambah Guru</button>
    </form>
</div>

<script>
document.getElementById("form-tambah-guru").addEventListener("submit", function(e){
    e.preventDefault();
    let form = this;

    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Data guru akan ditambahkan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Tambahkan",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@endsection
