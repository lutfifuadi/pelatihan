@php
    $configData = Helper::appClasses();
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance — Sistem Sedang Dalam Pemeliharaan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Sora:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin: 0; padding: 0; box-sizing: border-box; }

        html, body {
            height: 100%;
            overflow: hidden;
            font-family: 'Outfit', sans-serif;
            background-color: #0b0f19;
            color: #f8fafc;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .maintenance-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            min-height: 100dvh;
            padding: 24px;
            position: relative;
            background-color: #0b0f19;
            background-image:
                radial-gradient(at 20% 30%, rgba(99, 102, 241, 0.12) 0px, transparent 55%),
                radial-gradient(at 80% 70%, rgba(236, 72, 153, 0.10) 0px, transparent 55%),
                radial-gradient(at 50% 50%, rgba(6, 182, 212, 0.06) 0px, transparent 50%);
        }

        .glow-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(120px);
            opacity: 0.3;
            mix-blend-mode: screen;
            pointer-events: none;
            animation: orbFloat 20s infinite alternate ease-in-out;
        }
        .orb-1 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, #6366f1 0%, transparent 70%);
            top: -10%; left: -10%;
        }
        .orb-2 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, #ec4899 0%, transparent 70%);
            bottom: -10%; right: -10%;
            animation-duration: 25s;
        }
        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            50% { transform: translate(40px, 30px) scale(1.05) rotate(180deg); }
            100% { transform: translate(-20px, -40px) scale(0.95) rotate(360deg); }
        }

        .maintenance-card {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 520px;
            width: 100%;
            padding: 48px 40px;
            background: rgba(15, 23, 42, 0.35);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 20px;
            box-shadow: 0 30px 80px rgba(0, 0, 0, 0.5);
            animation: cardEntrance 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardEntrance {
            0% { opacity: 0; transform: translateY(30px) scale(0.96); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }

        .maintenance-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 28px;
            border-radius: 50%;
            background: rgba(99, 102, 241, 0.12);
            border: 1px solid rgba(99, 102, 241, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulseGlow 3s ease-in-out infinite;
        }
        .maintenance-icon i {
            font-size: 36px;
            color: #818cf8;
        }
        @keyframes pulseGlow {
            0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.2); }
            50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.4); }
        }

        .maintenance-title {
            font-family: 'Sora', sans-serif;
            font-size: clamp(1.5rem, 4vw, 2rem);
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 16px;
            line-height: 1.3;
        }
        .maintenance-message {
            font-size: clamp(0.95rem, 2vw, 1.1rem);
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.7;
            margin-bottom: 24px;
            max-width: 420px;
            margin-left: auto;
            margin-right: auto;
        }

        .maintenance-time {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 193, 7, 0.08);
            border: 1px solid rgba(255, 193, 7, 0.15);
            border-radius: 50px;
            font-size: 0.85rem;
            color: #fbbf24;
            margin-bottom: 32px;
        }
        .maintenance-time i {
            font-size: 16px;
        }

        .maintenance-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 32px;
            border-radius: 10px;
            font-family: 'Sora', sans-serif;
            font-weight: 600;
            font-size: 0.9rem;
            background: linear-gradient(135deg, #6366f1, #818cf8);
            color: #ffffff;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .maintenance-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.35);
            background: linear-gradient(135deg, #818cf8, #a5b4fc);
        }
        .maintenance-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 480px) {
            .maintenance-card {
                padding: 32px 24px;
                border-radius: 16px;
            }
            .maintenance-icon {
                width: 64px;
                height: 64px;
                margin-bottom: 24px;
            }
            .maintenance-icon i { font-size: 28px; }
        }
    </style>
</head>
<body>
    <div class="maintenance-wrapper">
        <div class="glow-orb orb-1"></div>
        <div class="glow-orb orb-2"></div>

        <div class="maintenance-card" role="alert" aria-live="polite">
            <div class="maintenance-icon">
                <i class="icon-base ti tabler-server-cog"></i>
            </div>

            <h1 class="maintenance-title">{{ $title ?? 'Sistem Sedang Dalam Pemeliharaan' }}</h1>

            <p class="maintenance-message">{{ $message ?? 'Kami sedang melakukan pemeliharaan rutin untuk meningkatkan layanan.' }}</p>

            @if(!empty($estimatedTime))
            <div class="maintenance-time">
                <i class="icon-base ti tabler-clock"></i>
                <span>Estimasi selesai: <strong>{{ $estimatedTime }}</strong></span>
            </div>
            @endif

            <a href="{{ url()->current() }}" class="maintenance-btn">
                <i class="icon-base ti tabler-refresh"></i>
                Coba Lagi
            </a>
        </div>
    </div>
</body>
</html>
