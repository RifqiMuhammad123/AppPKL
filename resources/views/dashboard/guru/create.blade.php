@extends('layouts.admin-shell')
@section('title','Tambah Guru')

<link rel="stylesheet" href="{{ asset('css/form.css') }}">

@section('content')
<div class="form-dashboard">
    <h2 style="text-align:center;">Tambah Guru</h2>
   <form id="form-tambah-guru" action="{{ route('admin.guru.store') }}" method="POST" autocomplete="off">
        @csrf
        <label for="nip">NIP</label>
        <input type="number" id="nip" name="nip" required autocomplete="off"
        oninput="if(this.value.length > 18) this.value = this.value.slice(0,18);">

        <label for="nama_guru">Nama Guru</label>
        <input type="text" name="nama_guru" id="nama_guru" required autocomplete="off">

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required autocomplete="new-password">


        <button type="submit" class="btn-submit">Tambah Guru</button>
        <button type="button" class="btn btn-secondary" onclick="konfirmasiKembali()">Kembali</button>
    </form>
</div>

<script>
    

    window.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-tambah-guru');
            if (!form.dataset.initialized) {
        form.querySelectorAll('input').forEach(input => {
            // Kosongkan hanya input type text/password, jangan yang type hidden
            if(input.type === 'text' || input.type === 'password') {
                input.value = '';
            }
        });
        form.dataset.initialized = "true";
            }
        });

    // alert jika ada perubahan pada form dan user mencoba kembali
    function konfirmasiKembali() {
    const form = document.getElementById('form-tambah-guru');

    // Cek apakah ada inputan yang diisi
    let isDirty = false;
    Array.from(form.elements).forEach(el => {
        if (el.tagName === 'INPUT' && el.value.trim() !== '') {
            isDirty = true;
        }
    });

    if (isDirty) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Perubahan yang belum disimpan akan hilang!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, kembali',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                history.back();
            }
        });
    } else {
        history.back(); // Kalau form kosong langsung kembali
    }
}

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
