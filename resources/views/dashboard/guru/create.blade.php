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
        <div class="password-wrapper">
            <input type="password" name="password" id="password" required autocomplete="new-password">
            <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
        </div>

        <button type="submit" class="btn-submit">Tambah Guru</button>
        <button type="button" class="btn btn-secondary" onclick="konfirmasiKembali()">Kembali</button>
    </form>
</div>

<style>
/* Hilangkan ikon mata bawaan Edge/Chrome */
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear {
  display: none !important;
  width: 0;
  height: 0;
}

input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="password"]::-webkit-textfield-decoration-container,
input[type="password"]::-webkit-textfield-decoration-button {
  display: none !important;
  visibility: hidden;
  -webkit-appearance: none;
}

/* Wrapper password + ikon */
.password-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.password-wrapper input {
  flex: 1;
  padding-right: 35px; /* ruang untuk ikon */
}

.toggle-password {
  position: absolute;
  right: 10px;
  top: 8px;
  cursor: pointer;
  font-size: 14px;
  color: #444;
}
</style>

<script>
    window.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('form-tambah-guru');
        if (!form.dataset.initialized) {
            form.querySelectorAll('input').forEach(input => {
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
            history.back();
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

    // Toggle password
    const toggle = document.getElementById("togglePassword");
    const input = document.getElementById("password");

    toggle.addEventListener("click", function () {
        const type = input.type === "password" ? "text" : "password";
        input.type = type;
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
    });
</script>
@endsection
