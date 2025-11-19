<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Riwayat Permintaan</title>
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
      border-bottom: 2px solid black;
      padding-bottom: 5px;
    }

    .school-header h2 {
      font-size: 16px;
      font-weight: bold;
      text-transform: uppercase;
      margin: 0 0 2px 0;
      line-height: 1.1;
    }

    .school-header span {
      display: block;
      font-size: 12px;
      line-height: 1.5;
      margin: 0;
      padding: 0;
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
      margin: 0;
    }

    /* TABLE */
    table {
      width: 100%;
      border-collapse: collapse;
      font-size: 11px;
      margin-top: 10px;
    }

    th {
      border: 1px solid #000;
      padding: 6px 4px;
      text-align: center;
      background-color: #d9d9d9;
      font-weight: bold;
    }

    td {
      border: 1px solid #000;
      padding: 6px 4px;
      text-align: center;
      background-color: #ffffff;
    }

    tbody tr:nth-child(even) td {
      background-color: #f2f2f2;
    }

    td.left {
      text-align: left;
      padding-left: 6px;
    }

    td.right {
      text-align: right;
      padding-right: 6px;
    }

    /* FOOTER TANDA TANGAN */
    .signature {
      text-align: right;
      width: 40%;
      margin-top: 30px;
    }

    .signature .date {
      display: block;
      margin-bottom: 60px;
    }

    .signature .name {
      font-weight: bold;
      text-decoration: underline;
      display: block;
      text-align: right;
    }

    .signature .nip {
      display: block;
      font-size: 11px;
      text-align: right;
    }

  </style>
</head>
<body>
  <!-- HEADER -->
  <div class="school-header">
    <h2>SMK MAHARDHIKA BATUJAJAR</h2>
    <span>Jl. Raya Batujajar No.30, Cangkorah Desa Giri Asih, Kec. Batujajar, Kab. Bandung Barat</span>
    <br>
    <span>Telp: (022) 6868495 | Website: http://www.smkmahardhika.sch.id | Email: smk.mahardhika.btjr.@gmail.com</span>
    <br>
    <span>Batujajar - 40561</span>
  </div>

  <div class="container">
    <!-- Judul -->
    <div class="report-title">
      <h1>Riwayat Permintaan</h1>
    </div>

    <!-- Tabel -->
    <table>
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Guru</th>
          <th>Nama Barang</th>
          <th>Merk</th>
          <th>Jumlah</th>
          <th>Tanggal</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @foreach($riwayatPermintaan as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td class="left">{{ $item->nama_guru }}</td>
          <td class="left">{{ $item->nama_barang }}</td>
          <td class="left">{{ $item->merk_barang }}</td>
          <td>{{ $item->jumlah }}</td>
          <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
          <td>{{ ucfirst($item->status) }}</td>
        </tr>
        @endforeach
      </tbody>
    </table>

    <!-- Footer Tanda Tangan -->
    <div class="signature">
      <span class="date">Bandung, {{ \Carbon\Carbon::now()->format('d F Y') }}</span>
      <br><br><br><br> <!-- spasi untuk tanda tangan -->
      <span class="name">Hj. Nia Herdiani, S.E., M.Pd</span>
      <br><span class="nip">NIP. 19750904 200501 2 001</span>
    </div>
  </div>
</body>
</html>