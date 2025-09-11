@extends('layouts.admin-shell') {{-- sesuaikan dengan layout utama kamu --}}

@section('content')
<div class="container">
    <h2>Edit Data Guru</h2>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

:root {
  --accent: #1e88e5;
  --accent2: #42a5f5;
  --muted: #7b7193;
  --radius: 8px;
}

/* Reset */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial;
}

/* Container */
.container {
  max-width: 500px;
  margin: 50px auto;
  padding: 30px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
  animation: fadeIn 0.3s ease;
}

/* Animasi masuk */
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

/* Judul */
.container h2 {
  text-align: center;
  margin-bottom: 25px;
  color: #222;
  font-size: 22px;
  font-weight: 600;
}

.form-group {
    margin-bottom: 14px;
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-size: 14px;
    color: var(--muted);
    margin-bottom: 4px;
}

.form-group input {
    border: none;
    border-bottom: 2px solid #ddd;
    padding: 6px 4px;
    font-size: 13px;
    background: transparent;
    outline: none;
    transition: border-color 0.2s ease;
}

.form-group input:focus {
    border-color: var(--accent);
}

/* Tombol */
.btn {
  padding: 10px 16px;
  border-radius: var(--radius);
  font-size: 14px;
  border: none;
  cursor: pointer;
  transition: background 0.2s ease, transform 0.15s ease;
}

.btn-primary {
  background: linear-gradient(90deg, var(--accent), var(--accent2));
  color: white;
}

.btn-primary:hover {
  transform: translateY(-2px);
  box-shadow:0 6px 16px rgba(30,136,229,.35);
}

.btn-secondary {
  background: #e53935;
  color: #fff;
  text-decoration: none;
  display: inline-block;
  text-align: center;
}

.btn-secondary:hover {
    transform: translateY(-2px);
    box-shadow:0 6px 16px rgba(229,57,53,.35);
}

</style>

<form action="{{ route('admin.guru.update', $guru->id_guru) }}" method="POST" id="form-edit-guru">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label for="nip">NIP</label>
        <input type="text" name="nip" value="{{ old('nip', $guru->nip) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="nama_guru">Nama Guru</label>
        <input type="text" name="nama_guru" value="{{ old('nama_guru', $guru->nama_guru) }}" class="form-control" required>
    </div>

    <div class="form-group">
        <label for="password">Password (biarkan kosong jika tidak ingin mengubah)</label>
        <input type="password" name="password" class="form-control" autocomplete="new-password">
    </div>
    
    <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('admin.guru.index') }}'">Kembali</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-edit-guru');

    // Ambil nilai awal
    const originalData = {
        nip: form.nip.value,
        nama_guru: form.nama_guru.value,
        password: '' // password awal dianggap kosong
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault(); // cegah submit default

        // Ambil nilai sekarang
        const currentData = {
            nip: form.nip.value,
            nama_guru: form.nama_guru.value,
            password: form.password.value
        };

        // Cek apakah ada perubahan
        const changed = currentData.nip !== originalData.nip ||
                        currentData.nama_guru !== originalData.nama_guru ||
                        currentData.password.trim() !== '';

        if (!changed) {
            // Tidak ada perubahan → langsung kembali
            window.location.href = '{{ route('admin.guru.index') }}';
        } else {
            // Ada perubahan → munculkan konfirmasi
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data guru akan diperbarui!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // submit form asli tanpa loop
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                }
            });
        }
    });
});
</script>
@endsection
