@extends('layouts.dashboard')

@section('title', 'Permintaan Barang')

@section('content')
<div class="form-dashboard">
    <h2>Ajukan Permintaan Barang</h2>
    <form action="{{ route('permintaan.store') }}" method="POST" id="form-permintaan">
        @csrf
        <label for="nama_barang">Nama Barang</label>
        <input type="text" name="nama_barang" id="nama_barang" required>

        <label for="merk_barang">Merk Barang</label>
        <input type="text" name="merk_barang" id="merk_barang" required>

        <label for="tanggal">Tanggal</label>
        <input type="date" name="tanggal" id="tanggal" required>

        <label for="jumlah">Jumlah Stok</label>
        <input type="number" name="jumlah" id="jumlah" min="1" required>

        <button type="submit" class="btn-submit">Ajukan</button>
    </form>
</div>

<!-- Daftar Permintaan -->
<div class="table-container">
    <table class="table-dashboard">
        <thead>
            <tr>
                <th>Nama Barang</th>
                <th>Merk</th>
                <th>Tanggal</th>
                <th>Jumlah</th>
                <th>Status</th>
                @if(session('auth_role') == 'admin')
                    <th>Aksi</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($permintaan as $p)
                <tr>
                    <td>{{ $p->nama_barang }}</td>
                    <td>{{ $p->merk_barang }}</td>
                    <td>{{ $p->tanggal }}</td>
                    <td>{{ $p->jumlah }}</td>
                    <td>
                        @if($p->status == 'pending')
                            <span style="color:orange;">⏳ Pending</span>
                        @elseif($p->status == 'dikonfirmasi')
                            <span style="color:green;">✔️ Dikonfirmasi</span>
                        @else
                            <span style="color:red;">❌ Ditolak</span>
                        @endif
                    </td>
                    @if(session('auth_role') == 'admin')
                        <td>
                            <form action="{{ route('permintaan.konfirmasi', $p->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn edit" title="Konfirmasi">
                                    <i class="fas fa-check"></i>
                                </button>
                            </form>
                            <form action="{{ route('permintaan.tolak', $p->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="action-btn delete" title="Tolak">
                                    <i class="fas fa-times"></i>
                                </button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align:center;">Belum ada permintaan</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
document.getElementById("form-permintaan").addEventListener("submit", function(e){
    e.preventDefault();
    let form = this;

    Swal.fire({
        title: "Apakah Anda yakin?",
        text: "Permintaan barang akan diajukan!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Ya, Ajukan",
        cancelButtonText: "Batal"
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});
</script>
@endsection
