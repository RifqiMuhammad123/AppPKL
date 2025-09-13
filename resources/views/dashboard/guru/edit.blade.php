@extends('layouts.admin-shell')

@section('content')
<div class="container">
    <h2>Edit Data Guru</h2>
    <style>
        :root {
          --accent: #1e88e5;
          --accent2: #42a5f5;
          --muted: #7b7193;
          --radius: 8px;
        }

        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
          font-family: 'Inter',system-ui,-apple-system,Segoe UI,Roboto,Arial;
        }

        .container {
          max-width: 500px;
          margin: 50px auto;
          padding: 30px;
          background: #fff;
          border-radius: 12px;
          box-shadow: 0px 4px 12px rgba(0,0,0,0.1);
          animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
          from { opacity: 0; transform: translateY(10px); }
          to { opacity: 1; transform: translateY(0); }
        }

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
          background: #f44336;
          color: #fff;
          text-decoration: none;
          display: inline-block;
          text-align: center;
        }


        .btn-secondary:hover {
         transform: translateY(-2px);
         box-shadow: 0 6px 16px rgba(244,67,54,.35);
        }

        .password-wrapper {
          position: relative;
          display: flex;
          align-items: center;
        }

        .password-wrapper input {
          width: 100%;
          padding-right: 36px;
        }

        .toggle-password {
          position: absolute;
          right: 8px;
          top: 50%;
          transform: translateY(-50%);
          cursor: pointer;
          color: #666;
          font-size: 16px;
        }

        .toggle-password:hover {
          color: var(--accent);
        }

        /* Hilangkan ikon bawaan */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear {
          display: none;
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
            <div class="password-wrapper">
                <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">
                <i class="fa-solid fa-eye toggle-password" id="togglePassword"></i>
            </div>
        </div>
        
        <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('admin.guru.index') }}'">Kembali</button>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
function togglePassword() {
    const input = document.getElementById("password");
    const icon = document.getElementById("togglePassword");

    if (input.type === "password") {
        input.type = "text";
        icon.classList.remove("fa-eye");
        icon.classList.add("fa-eye-slash");
    } else {
        input.type = "password";
        icon.classList.remove("fa-eye-slash");
        icon.classList.add("fa-eye");
    }
}

document.getElementById("togglePassword").addEventListener("click", togglePassword);

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-edit-guru');

    const originalData = {
        nip: form.nip.value,
        nama_guru: form.nama_guru.value,
        password: ''
    };

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const currentData = {
            nip: form.nip.value,
            nama_guru: form.nama_guru.value,
            password: form.password.value
        };

        const changed = currentData.nip !== originalData.nip ||
                        currentData.nama_guru !== originalData.nama_guru ||
                        currentData.password.trim() !== '';

        if (!changed) {
            window.location.href = '{{ route('admin.guru.index') }}';
        } else {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data guru akan diperbarui!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Update',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.removeEventListener('submit', arguments.callee);
                    form.submit();
                }
            });
        }
    });
});
</script>
@endsection
