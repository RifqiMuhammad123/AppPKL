<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #0d47a1;
            padding-bottom: 15px;
        }

        .header h1 {
            color: #0d47a1;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 12px;
        }

        .info-box {
            background: #f5f5f5;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
        }

        .info-item {
            font-size: 11px;
        }

        .info-item strong {
            color: #0d47a1;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        thead {
            background: #0d47a1;
            color: white;
        }

        th {
            padding: 10px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            font-size: 11px;
        }

        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }

        tbody tr:hover {
            background: #f0f0f0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 2px solid #0d47a1;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .warning-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin-bottom: 20px;
            font-size: 11px;
        }

        .warning-box strong {
            color: #856404;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DATA ADMIN SISTEM</h1>
        <p>Dokumen Rahasia - Backup Data Administrator</p>
    </div>

    <div class="info-box">
        <div class="info-item">
            <strong>Total Admin:</strong> {{ $admins->count() }}
        </div>
        <div class="info-item">
            <strong>Tanggal Cetak:</strong> {{ date('d F Y, H:i') }} WIB
        </div>
        <div class="info-item">
            <strong>Dicetak Oleh:</strong> {{ session('auth_name') }}
        </div>
    </div>

    <div class="warning-box">
        <strong>⚠️ PERHATIAN:</strong> Dokumen ini berisi informasi sensitif. Harap simpan dengan aman dan jangan disebarkan kepada pihak yang tidak berkepentingan.
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 20%;">NIP</th>
                <th style="width: 37%;">Nama Admin</th>
                <th style="width: 35%;">Password</th>
            </tr>
        </thead>
        <tbody>
            @foreach($admins as $index => $admin)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $admin->nip }}</td>
                <td>{{ $admin->nama_admin }}</td>
                <td><strong>{{ $admin->password_biasa ?? 'N/A' }}</strong></td>
            
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><strong>Catatan:</strong> Password yang tertera adalah password dalam bentuk plain text untuk keperluan recovery.</p>
        <p>Dokumen ini dihasilkan secara otomatis oleh sistem.</p>
        <p>&copy; {{ date('Y') }} Sistem Manajemen Admin - All Rights Reserved</p>
    </div>
</body>
</html>