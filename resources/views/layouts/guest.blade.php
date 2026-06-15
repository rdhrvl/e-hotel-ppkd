<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Welcome' }} — E-DN</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f0f1a;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(108,92,231,0.08) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(253,121,168,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 50% 80%, rgba(0,184,148,0.05) 0%, transparent 50%);
            color: #a0a0b8;
            padding: 20px;
        }

        .guest-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .guest-brand {
            text-align: center;
            margin-bottom: 32px;
        }

        .guest-brand h1 {
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe, #fd79a8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -1px;
        }

        .guest-brand p {
            font-size: 0.85rem;
            color: #5a5a7a;
            margin-top: 6px;
            letter-spacing: 2px;
            text-transform: uppercase;
            font-weight: 500;
        }

        .guest-card {
            background: #1a1a2e;
            border: 1px solid #2d2d44;
            border-radius: 20px;
            padding: 36px 32px;
            box-shadow:
                0 4px 24px rgba(0,0,0,0.3),
                0 0 80px rgba(108,92,231,0.04);
        }

        @media (max-width: 480px) {
            .guest-card {
                padding: 28px 20px;
                border-radius: 16px;
            }
        }

        /* ── Toast ── */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast {
            padding: 14px 20px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 500;
            color: #fff;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            animation: toastSlideIn 0.4s cubic-bezier(.4,0,.2,1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-success { background: linear-gradient(135deg, #00b894, #00a381); }
        .toast-error { background: linear-gradient(135deg, #e17055, #d63031); }

        @keyframes toastSlideIn {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
    </style>
</head>
<body>

    {{-- ── Flash Toasts ── --}}
    <div class="toast-container">
        @if (session('success'))
            <div class="toast toast-success"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave.duration.300ms>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="toast toast-error"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave.duration.300ms>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <div class="guest-wrapper">
        <div class="guest-brand">
            <h1>E-DN</h1>
            <p>Mobile Flow</p>
        </div>

        <div class="guest-card">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
