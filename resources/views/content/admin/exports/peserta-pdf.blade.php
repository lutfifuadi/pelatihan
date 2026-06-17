<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Peserta</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 11px;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            background-color: #4a5568;
            color: #fff;
            font-weight: bold;
            padding: 6px 4px;
            text-align: left;
            font-size: 9px;
        }
        table td {
            padding: 4px;
            border-bottom: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            font-size: 9px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 8px;
        }
        .page-number {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Data Peserta</h1>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 18%;">Nama</th>
                <th style="width: 14%;">NIK</th>
                <th style="width: 14%;">WhatsApp</th>
                <th style="width: 18%;">Email</th>
                <th style="width: 12%;">Kecamatan</th>
                <th style="width: 12%;">Kelurahan</th>
                <th style="width: 7%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pesertas as $index => $user)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->nik ?? '-' }}</td>
                <td>{{ $user->whatsapp ?? '-' }}</td>
                <td>{{ $user->email ?? '-' }}</td>
                <td>{{ $user->kecamatan?->name ?? '-' }}</td>
                <td>{{ $user->kelurahan?->name ?? '-' }}</td>
                <td>{{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #999; padding: 20px;">
                    Tidak ada data peserta.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>Total Peserta: {{ $pesertas->count() }}</span>
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>
</html>
