@extends('layouts.guru-shell')

@section('title','Ajukan Permintaan')

<link rel="stylesheet" href="{{ asset('css/form.css') }}">

@section('content')
<div class="form-container">
    <h2>Ajukan Permintaan Barang</h2>

    <form action="{{ route('guru.permintaan.store') }}" method="POST">
        @csrf

        <div class="form-group">
            <label>Nama Barang</label>
            <select name="id_barang" id="id_barang" class="form-control" required>
                <option value="" disabled selected>-- Pilih Barang --</option>
                @foreach($barang as $b)
                    <option value="{{ $b->id_barang }}" 
                            data-nama="{{ $b->nama_barang }}" 
                            data-merk="{{ $b->merk_barang }}"
                            data-stok="{{ $b->stok }}">
                        {{ $b->nama_barang }} - {{ $b->merk_barang }} (Stok: {{ $b->stok }})
                    </option>
                @endforeach
            </select>
            @error('id_barang')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Tanggal Permintaan</label>
            <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}" required>
            @error('tanggal')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-group">
            <label>Jumlah</label>
            <input type="number" name="jumlah" id="jumlah" class="form-control" min="1" placeholder="Masukkan jumlah" required>
            <small id="stok-info" style="color: #666; display: none;">Stok tersedia: <span id="stok-value">0</span></small>
            @error('jumlah')
                <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location='{{ route('guru.home') }}'">Kembali</button>
            <button type="submit" class="btn-submit">Ajukan</button>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Tampilkan info stok ketika barang dipilih
document.getElementById('id_barang').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const stok = selectedOption.getAttribute('data-stok');
    
    if (stok) {
        document.getElementById('stok-info').style.display = 'block';
        document.getElementById('stok-value').textContent = stok;
    }
});

// Validasi jumlah tidak melebihi stok
document.querySelector('form').addEventListener('submit', function(e) {
    const selectedOption = document.getElementById('id_barang').options[document.getElementById('id_barang').selectedIndex];
    const stok = parseInt(selectedOption.getAttribute('data-stok'));
    const jumlah = parseInt(document.getElementById('jumlah').value);
    
    if (jumlah > stok) {
        e.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'Jumlah Melebihi Stok!',
            text: `Stok tersedia hanya ${stok} unit`,
            confirmButtonColor: '#dc3545'
        });
    }
});

// Notifikasi sukses jika ada
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session("success") }}',
        timer: 2000,
        showConfirmButton: false
    });
@endif

// Notifikasi error jika ada
@if($errors->any())
    Swal.fire({
        icon: 'error',
        title: 'Oops...',
        html: '@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach',
        confirmButtonColor: '#dc3545'
    });
@endif
</script>

<style>
.form-container {
    max-width: 600px;
    margin: 30px auto;
    background: #fff;
    padding: 30px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.form-container h2 {
    margin-bottom: 25px;
    color: #333;
    font-size: 24px;
    font-weight: bold;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    color: #555;
    font-weight: 500;
}

.form-control, select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 14px;
    transition: border-color 0.3s;
}

.form-control:focus, select:focus {
    outline: none;
    border-color: #4CAF50;
    box-shadow: 0 0 0 3px rgba(76, 175, 80, 0.1);
}

select {
    cursor: pointer;
    background-color: #fff;
}

.text-danger {
    color: #dc3545;
    font-size: 12px;
    margin-top: 5px;
    display: block;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 30px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    transition: all 0.3s;
}

.btn-secondary {
    background: #ff0000ff;
    color: #fff;
}

.btn-secondary:hover {
    background: #ff0000ff;
}

.btn-submit {
    background: #27e637ff;
    color: #fff;
    flex: 1;
}

.btn-submit:hover {
    background: #4afc53ff;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(63, 250, 69, 1);
}

#stok-info {
    margin-top: 5px;
    font-size: 13px;
}

#stok-value {
    font-weight: bold;
    color: #4CAF50;
}
</style>
@endsection