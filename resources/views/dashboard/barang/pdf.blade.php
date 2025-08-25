<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Barang</title>
    <style>
        body { 
            font-family: "Times New Roman", serif; 
            font-size: 12px; 
            margin: 40px;
        }
        .kop {
            text-align: center;
            margin-bottom: 20px;
        }
        .kop h3, .kop p {
            margin: 2px;
        }
        .judul {
            text-align: center;
            margin: 30px 0 10px 0;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            font-size: 12px;
        }
        th, td { 
            border: 1px solid #000; 
            padding: 6px; 
            text-align: center;
        }
        th { 
            background-color: #eaeaea; 
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            width: 100%;
            display: flex;
            justify-content: flex-end;
        }
        .ttd {
            text-align: center;
            margin-right: 50px;
        }
        .ttd p {
            margin: 60px 0 0 0;
        }
    </style>
</head>
<body>
    <!-- Kop Laporan -->
    <div class="kop">
        <h3>SMK MAHARDHIKA BATUJAJAR</h3>
        <p>Jl. Raya Batujajar No. 30 Cangkorah</p>
        <p>Telp. (022) 6868494</p>
        <hr style="border: 1px solid #000;">
    </div>

    <!-- Judul -->
    <div class="judul">
        <h4>Laporan Data Barang</h4>
    </div>

    <!-- Tabel -->
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Merk</th>
                <th>Tanggal Pembelian</th>
                <th>Asal Usul</th>
                <th>Harga</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($barang as $index => $b)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td style="text-align: left;">{{ $b->nama_barang }}</td>
                <td style="text-align: left;">{{ $b->merk_barang }}</td>
                <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d-m-Y') }}</td>
                <td>{{ $b->asal_usul }}</td>
                <td style="text-align: right;">Rp{{ number_format($b->harga_barang, 0, ',', '.') }}</td>
                <td>{{ $b->stok }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Footer tanda tangan -->
    <div class="footer">
        <div class="ttd">
            <p>Bandung, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <!-- <p>Kepala Sekolah</p> -->
            <br><br><br>
            <!-- <p><u>Hj. Nia Herdiani, S.E. M.Pd</u></p> -->
        </div>
    </div>
</body>
</html>