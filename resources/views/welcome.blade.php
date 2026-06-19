<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[var(--bg-primary)]">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'HMS E-DN') }}</title>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full text-[var(--text-secondary)] antialiased flex flex-col items-center justify-center bg-[var(--bg-primary)] relative overflow-hidden px-4">
    <!-- Subtle radial warm spot behind the layout -->
    <div class="absolute inset-0 z-0 pointer-events-none opacity-20">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-radial from-[#f4f3ef] to-transparent rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 w-full max-w-md text-center space-y-8">
        <!-- Brand Logo & Header -->
        <div class="space-y-4">
            <div class="inline-flex h-12 w-12 items-center justify-center rounded bg-[var(--text-primary)] font-bold text-[var(--bg-card)] text-xl">
                H
            </div>
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-[var(--text-primary)]">
                    HMS E-DN
                </h1>
                <p class="mt-2 text-xs font-bold uppercase tracking-widest text-[var(--text-muted)]">
                    Hotel Management System
                </p>
            </div>
        </div>

        <!-- Action Box -->
        <div class="rounded-lg border border-[var(--border-color)] bg-[var(--bg-card)] p-8 shadow-sm space-y-6">
            <p class="text-sm text-[var(--text-secondary)] leading-relaxed">
                A streamlined administration tool tailored for hotel operations, reservation tracking, guest services, billing desk control, and real-time housekeeping auditing.
            </p>

            <div class="flex flex-col items-stretch gap-3">
                @auth
                    <a href="{{ url('/dashboard') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded bg-[var(--text-primary)] font-semibold text-[var(--bg-card)] text-sm hover:bg-[#333333] transition-all duration-150 active:scale-[0.98]">
                        Go to Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded bg-[var(--text-primary)] font-semibold text-[var(--bg-card)] text-sm hover:bg-[#333333] transition-all duration-150 active:scale-[0.98]">
                        Sign In to Console
                    </a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded border border-[var(--border-color)] bg-[var(--bg-card)] text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)] hover:text-[var(--text-primary)] text-sm font-semibold transition-all duration-150 active:scale-[0.98]">
                            Register Account
                        </a>
                    @endif
                @endauth
            </div>
        </div>

        <div class="text-xs text-[var(--text-muted)]">
            Powered by Laravel v{{ app()->version() }} &bull; HMS E-DN v1.0.0
        </div>
    </div>
</body>
</html>
