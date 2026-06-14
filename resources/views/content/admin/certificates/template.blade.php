<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 0;
            padding: 0;
        }
        body {
            font-family: 'Outfit', 'Segoe UI', sans-serif;
            margin: 0;
            padding: 0;
            background: #0b0f19;
            color: #f8fafc;
        }
        .certificate-wrapper {
            width: 100%;
            height: 100%;
            position: relative;
            overflow: hidden;
        }
        .certificate-border {
            position: absolute;
            top: 20px;
            left: 20px;
            right: 20px;
            bottom: 20px;
            border: 2px solid rgba(255, 215, 0, 0.6);
            border-radius: 5px;
            pointer-events: none;
        }
        .certificate-inner {
            padding: 60px 80px;
            text-align: center;
            position: relative;
            z-index: 1;
        }
        .header-title {
            font-size: 14px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255, 215, 0, 0.8);
            margin-bottom: 10px;
            font-weight: 600;
        }
        .main-title {
            font-size: 42px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 5px;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
            font-style: italic;
        }
        .divider {
            width: 200px;
            height: 2px;
            background: linear-gradient(90deg, transparent, rgba(255, 215, 0, 0.8), transparent);
            margin: 0 auto 30px auto;
        }
        .body-text {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.7);
            margin-bottom: 5px;
        }
        .participant-name {
            font-size: 36px;
            font-weight: 800;
            color: #ffd700;
            margin: 15px 0;
            letter-spacing: 1px;
        }
        .training-name {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin: 10px 0;
        }
        .training-info {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.6);
            margin-bottom: 30px;
        }
        .cert-number {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.4);
            margin-top: 40px;
            letter-spacing: 1px;
        }
        .footer {
            position: absolute;
            bottom: 40px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 11px;
            color: rgba(255, 255, 255, 0.3);
        }
        .gold-text {
            color: #ffd700;
        }
        .qr-section {
            margin-top: 20px;
        }
        .qr-section img {
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="certificate-wrapper">
        <div class="certificate-border"></div>
        <div class="certificate-inner">
            <div class="header-title">Sertifikat Kelulusan</div>
            <div class="main-title">Sertifikat</div>
            <div class="subtitle">— Pelatihan Ekonomi Kreatif —</div>
            <div class="divider"></div>

            <div class="body-text">Diberikan kepada:</div>
            <div class="participant-name">{{ $participant->name }}</div>

            <div class="body-text">Telah berhasil menyelesaikan pelatihan</div>
            <div class="training-name">{{ $training->nama }}</div>
            <div class="training-info">
                Batch {{ $training->batch }}
                @if($training->tanggal_mulai && $training->tanggal_selesai)
                    | {{ \Carbon\Carbon::parse($training->tanggal_mulai)->format('d F Y') }} -
                    {{ \Carbon\Carbon::parse($training->tanggal_selesai)->format('d F Y') }}
                @endif
            </div>

            <div class="divider"></div>

            <div class="body-text" style="font-size: 12px;">
                Diterbitkan pada: {{ now()->format('d F Y') }}
            </div>

            <div class="cert-number">
                No. Sertifikat: {{ $certificateNumber ?? $certificate->certificate_number ?? '-' }}
            </div>

            <div class="qr-section">
                <div style="font-size: 10px; color: rgba(255,255,255,0.4); margin-bottom: 5px;">
                    Scan untuk verifikasi
                </div>
                {{-- QR Code akan digenerate secara terpisah --}}
            </div>
        </div>
        <div class="footer">
            Sistem Pelatihan Ekonomi Kreatif
        </div>
    </div>
</body>
</html>
