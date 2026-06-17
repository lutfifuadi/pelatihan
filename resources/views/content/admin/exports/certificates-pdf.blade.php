<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Sertifikat</title>
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
        <h1>Data Sertifikat</h1>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
        @if($pelatihan)
            <p>Pelatihan: {{ $pelatihan->nama }} (Batch: {{ $pelatihan->batch }})</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Peserta</th>
                <th style="width: 15%;">NIK</th>
                <th style="width: 25%;">Pelatihan</th>
                <th style="width: 22%;">Nomor Sertifikat</th>
                <th style="width: 13%;">Tanggal Terbit</th>
            </tr>
        </thead>
        <tbody>
            @forelse($certificates as $index => $certificate)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $certificate->enrollment?->user?->name ?? '-' }}</td>
                <td>{{ $certificate->enrollment?->user?->nik ?? '-' }}</td>
                <td>{{ $certificate->enrollment?->pelatihan?->nama ?? '-' }}</td>
                <td>{{ $certificate->certificate_number ?? '-' }}</td>
                <td>{{ $certificate->issued_at ? $certificate->issued_at->format('d-m-Y') : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align: center; color: #999; padding: 20px;">
                    Tidak ada data sertifikat.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>Total Sertifikat: {{ $certificates->count() }}</span>
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>
</html>
