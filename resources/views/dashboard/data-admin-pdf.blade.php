<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Admin Sistem - Cetak</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
        }

        /* KOP */
        .kop {
            text-align: center;
            margin-bottom: 5px;
        }

        .title {
            font-size: 15px;
            font-weight: bold;
        }

        .addr {
            font-size: 10px;
            margin: 0;
            line-height: 8px;
        }

        .kop-line {
            border-top: 1px solid #000;
            margin-top: 5px;
            margin-bottom: 12px;
        }

        /* JUDUL */
        .judul {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        /* INFO */
        .info {
            line-height: 16px;
            font-size: 11px;
        }


        /* ====== TABEL RAPIH ====== */
        table {
            width: 100%;
            border: 1px solid #000;
            font-size: 11px;
            border-spacing: 0; /* wajib untuk TCPDF */
        }

        table th {
            border: 1px solid #000;
            padding: 4px;
            font-weight: bold;
            text-align: center;
            background-color: #f2f2f2;
        }

        table td {
            border: 1px solid #000;
            padding: 4px;
        }

        td.left {
            text-align: left;
        }

        /* FOOTER */
        .printed-at {
            text-align: left;
            margin-top: 10px;
            font-size: 10px;
        }

        .footer {
        margin-top: 40px;
        width: 100%;
        display: flex;
        justify-content: flex-end;
        }

        .signature {
        text-align: righ;
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

<div class="sheet">

    <!-- KOP -->
    <div class="kop">
        <div class="title">SMK MAHARDHIKA BATUJAJAR</div>
        <div class="addr">Jl. Raya Batujajar No.30, Cangkorah Desa Giri Asih, Kec. Batujajar, Kab. Bandung Barat</div>
        <div class="addr">Telp: (022) 6868495 | Website: http://www.smkmahardhika.sch.id | Email: smk.mahardhika.btjr.@gmail.com</div>
        <div class="addr">Batujajar - 40561</div>
        <div class="kop-line"></div>
    </div>

    <!-- JUDUL -->
    <div class="judul">Laporan Data Admin Sistem</div>

    <!-- INFO -->
    <div class="info">
        <strong>Total Admin:</strong> {{ $admins->count() }}<br>
        <strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB<br>
        <strong>Dicetak Oleh:</strong> {{ session('auth_name') }}
    </div>

    <!-- TABEL -->
    <br><br>
    <table border="1" cellpadding="4" cellspacing="0" width="100%" style="margin-top:25px;">
    <thead>
        <tr style="font-weight: bold; background-color: #e5e5e5; text-align: center;">
            <th width="40">No</th>
            <th width="120">NIP</th>
            <th width="200">Nama Admin</th>
            <th width="120">Password</th>
        </tr>
    </thead>

    <tbody>
        @foreach($admins as $index => $admin)
        <tr>
            <td width="40" align="center">{{ $index + 1 }}</td>
            <td width="120" align="center">{{ $admin->nip }}</td>

            <td width="200" style="text-align:center;">
                {{ $admin->nama_admin }}
            </td>


            <td width="120" align="center">{{ $admin->password_plain }}</td>
        </tr>
        @endforeach
    </tbody>
    </table>

    <!-- FOOTER -->
    <br><br>
    <div class="printed-at">
        Dicetak otomatis oleh sistem — {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y') }}
    </div>

    <div class="footer">
      <div class="signature">
        <p class="date">Bandung, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>

        <p>&nbsp;</p>
        <p>&nbsp;</p>

        <p class="name">Hj. Nia Herdiani, S.E., M.Pd</p>
        <p class="nip">NIP. 19750904 200501 2 001</p>
      </div>
    </div>

</div>

</body>
</html>
