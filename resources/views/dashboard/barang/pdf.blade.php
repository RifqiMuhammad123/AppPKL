<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Laporan Data Barang</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    @page {
      margin: 10mm 15mm;
    }

    body {
      font-family: "Times New Roman", serif;
      font-size: 12px;
      line-height: 1.4;
      color: #000;
      margin: 0;
      padding: 0;
    }

    .container {
      padding: 0 15mm;
    }

    /* HEADER */
    .school-header {
      text-align: center;
      padding-top: 0;
      margin-top: 0;
      border-bottom: 2px solid black;
      padding-bottom: 5px;
    }

    .school-header h2 {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      margin: 0;
    }

    .school-header p {
      font-size: 12px;
      margin: 2px 0;
    }

    /* JUDUL */
    .report-title {
      text-align: center;
      margin: 15px 0;
    }

    .report-title h1 {
      font-size: 14px;
      font-weight: bold;
      text-transform: uppercase;
      text-decoration: underline;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
      margin-top: 10px;
    }

    th, td {
      border: 1px solid #000;
      padding: 6px 4px;
      text-align: center;
    }

    td.left {
      text-align: left;
      padding-left: 6px;
    }

    td.right {
      text-align: right;
      padding-right: 6px;
    }

    th {
      background-color: #f0f0f0;
      font-weight: bold;
    }

    /* FOOTER */
    .footer {
      margin-top: 40px;
      width: 100%;
      display: flex;
      justify-content: flex-end;
    }

    .signature {
      text-align: center;
      width: 40%;
    }

    .signature .date {
      margin-bottom: 60px;
    }

    .signature .name {
      font-weight: bold;
      text-decoration: underline;
    }

    .signature .nip {
      font-size: 11px;
    }
  </style>
</head>
<body>
  <div class="school-header">
    <h2>SMK MAHARDHIKA BATUJAJAR</h2>
    <p>Jl. Raya Batujajar No. 30 Cangkorah</p>
    <p>Telp. (022) 6868494</p>
  </div>

  <div class="container">
    <!-- Judul -->
    <div class="report-title">
      <h1>Laporan Data Barang</h1>
    </div>

    <!-- Tabel -->
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama</th>
          <th>Merk</th>
          <th>Tanggal Pembelian</th>
          <th>Harga</th>
          <th>Stok</th>
        </tr>
      </thead>
      <tbody>
        @foreach($barang as $index => $b)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="left">{{ $b->nama_barang }}</td>
          <td class="left">{{ $b->merk_barang }}</td>
          <td>{{ \Carbon\Carbon::parse($b->tanggal_pembelian)->format('d-m-Y') }}</td>
          <td class="right">Rp{{ number_format($b->harga_barang, 0, ',', '.') }}</td>
          <td>{{ $b->stok }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Footer -->
    <div class="footer">
      <div class="signature">
        <p class="date">Bandung, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
        <p class="name">Hj. Nia Herdiani, S.E., M.Pd</p>
        <p class="nip">NIP. 19750904 200501 2 001</p>
      </div>
    </div>
  </div>
</body>
</html>
