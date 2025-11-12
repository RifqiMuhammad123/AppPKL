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
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            color: #000;
            margin-bottom: 3px;
        }

        .header p {
            font-size: 11px;
            color: #444;
            letter-spacing: 0.3px;
        }

        /* === INFO === */
        .info-box {
            font-size: 11.5px;
            margin-bottom: 20px;
            padding: 8px 12px;
            border: 1px solid #000;
            border-radius: 4px;
            background-color: #fafafa;
        }

        .info-box p {
            margin: 4px 0;
        }

        .info-box strong {
            color: #000;
        }

        /* === TABEL === */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 11.5px;
        }

        thead {
            background-color: #e6e6e6;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        th, td {
            border: 1px solid #000;
            padding: 7px 5px;
            text-align: center;
        }

        th {
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        td.left {
            text-align: left;
            padding-left: 10px;
        }

        tbody tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        tbody tr:hover {
            background-color: #f2f2f2;
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
        <h1>LAPORAN DATA ADMIN SISTEM</h1>
        <p>Dokumen Rahasia - Backup Data Administrator</p>
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
                <td>
                    {{ $admin->password_plain }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Catatan:</strong> Password yang tertera adalah data asli yang tersimpan di sistem (jika tersedia).</p>
        <p>Jika tampil *HASHED* berarti password tersebut sudah terenkripsi dan tidak dapat ditampilkan.</p>
        <p><em>Dicetak otomatis oleh sistem — {{ date('d F Y') }}</em></p>
        <p>&copy; {{ date('Y') }} Sistem Manajemen Admin — All Rights Reserved</p>
    </div>
</body>
</html>