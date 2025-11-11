<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Admin Sistem</title>
    <style>
        /* === RESET === */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* === DASAR === */
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #000;
            background: #fff;
            padding: 40px 50px;
            line-height: 1.5;
        }

        /* === HEADER === */
        .header {
            text-align: center;
            position: relative;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header-logo-left {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .header-logo-right {
            position: absolute;
            right: 0;
            top: 0;
            width: 100px;
            height: 100px;
            object-fit: contain;
        }

        .header-center {
            padding: 0 120px;
        }

        .header h1 {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            margin-bottom: 5px;
            margin-top: 30px;
        }

        .header p {
            font-size: 12px;
            color: #444;
            letter-spacing: 0.3px;
            margin-bottom: 15px;
        }

        .header-line {
            border-bottom: 3px solid #000;
            margin-top: 15px;
        }

        /* === INFO === */
        .info-box {
            font-size: 11.5px;
            margin-top: 30px;
            margin-bottom: 20px;
        }

        .info-box p {
            margin: 6px 0;
        }

        .info-box strong {
            color: #000;
        }

        /* === TABEL === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 11.5px;
        }

        thead {
            background-color: #f0f0f0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
        }

        th {
            font-weight: bold;
            text-transform: capitalize;
        }

        td.left {
            text-align: left;
            padding-left: 15px;
        }

        tbody tr:nth-child(odd) {
            background-color: #fafafa;
        }

        tbody tr:hover {
            background-color: #f5f5f5;
        }

        /* === FOOTER === */
        .footer {
            margin-top: 40px;
            padding-top: 8px;
            border-top: 2px solid #000;
            text-align: center;
            font-size: 10.5px;
            color: #333;
        }

        .footer p {
            margin: 4px 0;
        }

        /* === CETAK === */
        @media print {
            body {
                padding: 20px;
                font-size: 11px;
            }
            .info-box {
                page-break-inside: avoid;
            }
            .footer {
                position: fixed;
                bottom: 20px;
                left: 0;
                right: 0;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <img src="{{ Storage::url('logo/logo_sekolah.png') }}" alt="Logo Sekolah" class="header-logo-left">
        <img src="{{ Storage::url('logo/logo_pt.png') }}" alt="Logo PT" class="header-logo-right">
        
        <div class="header-center">
            <h1>LAPORAN DATA ADMIN SISTEM</h1>
            <p>Dokumen Rahasia - Backup Data Administrator</p>
        </div>
        
        <div class="header-line"></div>
    </div>

    <div class="info-box">
        <p><strong>Total Admin:</strong> {{ $admins->count() }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('d F Y, H:i') }} WIB</p>
        <p><strong>Dicetak Oleh:</strong> {{ session('auth_name') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIP</th>
                <th>Nama Admin</th>
                <th>Password</th>
            </tr>
        </thead>
        <tbody>
            @php use Illuminate\Support\Str; @endphp
            @foreach($admins as $index => $admin)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $admin->nip }}</td>
                <td class="left">{{ $admin->nama_admin }}</td>
                <td>{{ $admin->password_plain }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Catatan:</strong> Password yang tertera adalah data asli yang tersimpan di sistem (jika tersedia).</p>
        <p>Jika tampil **HASHED** berarti password tersebut sudah terenkripsi dan tidak dapat ditampilkan.</p>
        <p><em>Dicetak otomatis oleh sistem — {{ date('d F Y') }}</em></p>
        <p>&copy; {{ date('Y') }} Sistem Manajemen Admin — All Rights Reserved</p>
    </div>
</body>
</html>