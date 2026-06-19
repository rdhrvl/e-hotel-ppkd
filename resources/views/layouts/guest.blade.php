<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Welcome' }} - HMS</title>

    {{-- Google Fonts: Outfit --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        if (localStorage.getItem('darkMode') === 'true' || 
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="guest-layout">

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
        <div class="guest-brand flex flex-col items-center justify-center mb-6">
            <img src="/img/logo-ppkd.png" alt="PPKD Logo" class="h-16 w-auto mb-3">
            <h1 class="text-2xl font-bold tracking-tight text-[var(--text-primary)]">HMS E-DN</h1>
            <p class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mt-1">Hotel Management System</p>
        </div>

        <div class="guest-card">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
