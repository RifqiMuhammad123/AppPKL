@extends('layouts.admin-shell')

@section('title', 'Riwayat Permintaan Barang')

@section('content')
<div class="container">
    <h2>Riwayat Permintaan Barang</h2>
    
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama Guru</th>
                    <th>Nama Barang</th>
                    <th>Merk</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayatPermintaan as $item)
                <tr>
                    <td>{{ date('d/m/Y', strtotime($item->tanggal)) }}</td>
                    <td>{{ $item->nama_guru }}</td>
                    <td>{{ $item->nama_barang }}</td>
                    <td>{{ $item->merk_barang }}</td>
                    <td>{{ $item->jumlah }}</td>
                    <td>
                        <span class="badge {{ $item->status == 'disetujui' ? 'badge-success' : 'badge-danger' }}">
                            {{ ucfirst($item->status) }}
                        </span>
                    </td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center">Belum ada riwayat permintaan</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection