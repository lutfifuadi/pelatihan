<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Data Pendaftaran (Enrollment)</title>
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
        .status-pending { color: #d97706; }
        .status-approved { color: #059669; }
        .status-rejected { color: #dc2626; }
        .status-waitlist { color: #6366f1; }
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
        <h1>Data Pendaftaran Pelatihan</h1>
        <p>Tanggal Cetak: {{ now()->format('d-m-Y H:i') }}</p>
        @if($pelatihan)
            <p>Pelatihan: {{ $pelatihan->nama }} (Batch: {{ $pelatihan->batch }})</p>
        @endif
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 16%;">Nama Peserta</th>
                <th style="width: 12%;">NIK</th>
                <th style="width: 13%;">WhatsApp</th>
                <th style="width: 16%;">Pelatihan</th>
                <th style="width: 10%;">Status</th>
                <th style="width: 14%;">Tanggal Daftar</th>
                <th style="width: 14%;">Tgl Approve/Reject</th>
            </tr>
        </thead>
        <tbody>
            @forelse($enrollments as $index => $enrollment)
            @php
                $tanggalApproveReject = '-';
                if ($enrollment->status?->value === 'approved' && $enrollment->approved_at) {
                    $tanggalApproveReject = $enrollment->approved_at->format('d-m-Y H:i');
                } elseif ($enrollment->status?->value === 'rejected' && $enrollment->rejected_at) {
                    $tanggalApproveReject = $enrollment->rejected_at->format('d-m-Y H:i');
                } elseif ($enrollment->status?->value === 'waitlist' && $enrollment->waitlist_promoted_at) {
                    $tanggalApproveReject = $enrollment->waitlist_promoted_at->format('d-m-Y H:i');
                }
            @endphp
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $enrollment->user?->pesertaProfile?->nama_lengkap ?: $enrollment->user?->name ?? '-' }}</td>
                <td>{{ $enrollment->user?->nik ?? '-' }}</td>
                <td>{{ $enrollment->user?->whatsapp ?? '-' }}</td>
                <td>{{ $enrollment->pelatihan?->nama ?? '-' }}</td>
                <td class="status-{{ $enrollment->status }}">{{ ucfirst($enrollment->status) }}</td>
                <td>{{ $enrollment->created_at ? $enrollment->created_at->format('d-m-Y H:i') : '-' }}</td>
                <td>{{ $tanggalApproveReject }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="text-align: center; color: #999; padding: 20px;">
                    Tidak ada data pendaftaran.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <span>Total Pendaftaran: {{ $enrollments->count() }}</span>
    </div>

    <div class="page-number">
        Halaman {PAGE_NUM} dari {PAGE_COUNT}
    </div>
</body>
</html>
