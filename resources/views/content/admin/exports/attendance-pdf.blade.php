<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rekapitulasi Absensi</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
        }
        .header p {
            margin: 3px 0 0;
            font-size: 10px;
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
            padding: 4px 2px;
            text-align: center;
            font-size: 7px;
        }
        table th.left {
            text-align: left;
        }
        table td {
            padding: 3px 2px;
            border-bottom: 1px solid #ddd;
            text-align: center;
        }
        table td.left {
            text-align: left;
        }
        table tr:nth-child(even) {
            background-color: #f9fafb;
        }
        .att-hadir { color: #059669; }
        .att-sakit { color: #d97706; }
        .att-izin { color: #3b82f6; }
        .att-alpa { color: #dc2626; }
        .footer {
            text-align: center;
            margin-top: 15px;
            font-size: 8px;
            color: #999;
            border-top: 1px solid #ddd;
            padding-top: 6px;
        }
        .page-number {
            position: fixed;
            bottom: 10px;
            right: 10px;
            font-size: 8px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Rekapitulasi Absensi</h1>
        <p>{{ $pelatihan->nama }} (Batch: {{ $pelatihan->batch }})</p>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }} | Total Pertemuan: {{ $totalPertemuan }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th class="left" style="width: 3%;">No</th>
                <th class="left" style="width: 16%;">Nama Peserta</th>
                <th style="width: 10%;">NIK</th>
                @foreach($pertemuans as $p)
                    <th style="width: {{ $totalPertemuan > 20 ? '3%' : '4%' }};">P{{ $p }}</th>
                @endforeach
                <th style="width: 7%;">Hadir</th>
                <th style="width: 6%;">Sakit</th>
                <th style="width: 5%;">Izin</th>
                <th style="width: 6%;">Alpa</th>
                <th style="width: 7%;">%</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $index => $enrollment)
            @php
                $totalHadir = $enrollment->attendances->where('status', 'hadir')->count();
                $totalSakit = $enrollment->attendances->where('status', 'sakit')->count();
                $totalIzin = $enrollment->attendances->where('status', 'izin')->count();
                $totalAlpa = $enrollment->attendances->where('status', 'alpa')->count();
                $persen = $totalPertemuan > 0 ? round(($totalHadir / $totalPertemuan) * 100, 1) : 0;
            @endphp
            <tr>
                <td class="left">{{ $loop->iteration }}</td>
                <td class="left">{{ $enrollment->user?->name ?? '-' }}</td>
                <td>{{ $enrollment->user?->nik ?? '-' }}</td>
                @foreach($pertemuans as $p)
                    @php
                        $att = $enrollment->attendances->firstWhere('pertemuan_ke', $p);
                        $status = $att ? $att->status : null;
                    @endphp
                    <td class="att-{{ $status ?? 'null' }}">
                        @if($status)
                            {{ ucfirst($status) }}
                        @else
                            -
                        @endif
                    </td>
                @endforeach
                <td>{{ $totalHadir }}</td>
                <td>{{ $totalSakit }}</td>
                <td>{{ $totalIzin }}</td>
                <td>{{ $totalAlpa }}</td>
                <td>{{ $persen }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="{{ 5 + count($pertemuans) }}" style="text-align: center; color: #999; padding: 20px;">
                    Belum ada data absensi.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>Total Peserta: {{ $enrollments->count() }}</span>
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>
</html>
