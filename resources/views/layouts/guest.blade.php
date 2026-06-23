<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Welcome' }} - PPKD Hotel</title>

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
<body class="guest-layout" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }">

    {{-- Invert Mode Toggle Button --}}
    <div class="fixed top-6 right-6 z-50">
        <button @click="darkMode = !darkMode; localStorage.setItem('darkMode', darkMode); document.documentElement.classList.toggle('dark', darkMode)" 
                class="relative border border-[var(--border-color)] p-2 text-[var(--text-primary)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] transition-all duration-100 cursor-pointer rounded-md shadow-sm"
                title="Toggle Theme"
                aria-label="Toggle Theme">
            <svg x-show="darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: none;" :style="{ display: darkMode ? 'block' : 'none' }">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21M4.929 4.929l1.591 1.591m10.97 10.97l1.591 1.591M3 12h2.25m13.5 0H21m-2.23-7.071l-1.591 1.591M6.52 17.48l-1.591 1.591M12 6.75a5.25 5.25 0 100 10.5 5.25 5.25 0 000-10.5z" />
            </svg>
            <svg x-show="!darkMode" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" style="display: none;" :style="{ display: !darkMode ? 'block' : 'none' }">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
            </svg>
        </button>
    </div>

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
            <img src="/img/logo-ppkdjp.png" alt="PPKD Logo" class="h-16 w-auto mb-3">
            <h1 class="text-2xl font-bold tracking-tight text-[var(--text-primary)]">PPKD Hotel</h1>
            <p class="text-xs font-bold uppercase tracking-widest text-[var(--text-muted)] mt-1">Hotel Management System</p>
        </div>

        <div class="guest-card">
            {{ $slot }}
        </div>
    </div>

    @livewireScripts
</body>
</html>
