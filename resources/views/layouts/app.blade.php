<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} - PPKD Hotel</title>

    <script>
        if (localStorage.getItem('darkMode') === 'true' || 
            (!('darkMode' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full text-[var(--text-secondary)] antialiased bg-[var(--bg-primary)]" x-data="{ sidebarOpen: false, darkMode: localStorage.getItem('darkMode') === 'true' }">

    {{-- Layout wrapper --}}
    <div class="flex h-screen overflow-hidden bg-[var(--bg-primary)]">
        
        {{-- Sidebar --}}
        <aside class="fixed inset-y-0 left-0 z-50 flex w-64 flex-col border-r border-[var(--border-color)] bg-[var(--bg-card)] transition-transform duration-300 ease-in-out lg:static lg:translate-x-0"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            {{-- Brand Logo --}}
            <div class="flex h-20 items-center justify-between px-5 border-b border-[var(--border-color)]">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                    <img src="/img/logo-ppkd.png" alt="PPKD Logo" class="h-[40px] w-auto">
                    <div>
                        <h1 class="text-[18px] font-bold tracking-tight text-[var(--text-primary)]">PPKD Hotel</h1>
                    </div>
                </a>
                <button @click="sidebarOpen = false" class="border border-[var(--border-color)] bg-[var(--bg-card)] p-1.5 text-[var(--text-primary)] hover:bg-[var(--bg-secondary)] rounded-md lg:hidden transition-colors">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Sidebar Nav --}}
            <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-6">
                <div>
                    <span class="px-3 text-xs font-bold uppercase tracking-widest text-[var(--text-muted)]">Overview</span>
                    <div class="mt-2 space-y-1">
                        {{-- 1. Dashboard --}}
                        <a href="{{ route('dashboard') }}" 
                           class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('dashboard') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" />
                            </svg>
                            Dashboard
                        </a>

                        {{-- Room Availability --}}
                        <a href="{{ route('room-availability') }}" 
                           class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('room-availability') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                            </svg>
                            Room Availability
                        </a>
                    </div>
                </div>

                <div>
                    <span class="px-3 text-xs font-bold uppercase tracking-widest text-[var(--text-muted)]">Manage</span>
                    <div class="mt-2 space-y-1">
                        {{-- Rooms --}}
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('rooms') }}" 
                               class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('rooms') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21h8.25M9.75 17.25h4.5M10.5 21V12a1.5 1.5 0 011.5-1.5h0a1.5 1.5 0 011.5 1.5v9m-7.5-6h12m-13.5-3.75V5.625c0-.621.504-1.125 1.125-1.125h14.75c.621 0 1.125.504 1.125 1.125V11.25" />
                                </svg>
                                Rooms
                            </a>
                        @endif

                        {{-- Bookings --}}
                        @if(auth()->user()->isAdmin() || auth()->user()->isFrontDesk())
                            <a href="{{ route('bookings') }}" 
                               class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('bookings') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                                Bookings
                            </a>
                        @endif

                        {{-- Guests --}}
                        <a href="{{ route('guests') }}" 
                           class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('guests') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.386 11.386 0 0110.089 20M3 11.625a3 3 0 116 0 3 3 0 01-6 0z" />
                            </svg>
                            Guests
                        </a>

                        {{-- Housekeeping --}}
                        <a href="{{ route('housekeeping') }}" 
                           class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('housekeeping') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766" />
                            </svg>
                            Housekeeping
                        </a>

                        {{-- Payments --}}
                        <a href="{{ route('payments') }}" 
                           class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('payments') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0c1.172-.879 1.172-2.303 0-3.182" />
                            </svg>
                            Payments
                        </a>

                        {{-- Food & Beverage --}}
                        @if(auth()->user()->isFnb())
                            <a href="{{ route('fnb') }}" 
                               class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('fnb') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                                </svg>
                                Food & Beverage
                            </a>
                        @endif
                    </div>
                </div>

                <div>
                    <span class="px-3 text-xs font-bold uppercase tracking-widest text-[var(--text-muted)]">System</span>
                    <div class="mt-2 space-y-1">
                        {{-- Settings --}}
                        <a href="{{ route('settings') }}" 
                           class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('settings') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 010 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 010-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28z" />
                            </svg>
                            Settings
                        </a>

                        {{-- Audit Trails --}}
                        @if(auth()->user()->isAdmin())
                            <a href="{{ route('audit-logs') }}" 
                               class="flex items-center gap-3 rounded px-3 py-2 text-sm font-medium transition-colors duration-150 {{ request()->routeIs('audit-logs') ? 'bg-[var(--bg-secondary)] text-[var(--text-primary)] font-bold border border-[var(--border-color)]' : 'text-[var(--text-secondary)] hover:bg-[var(--bg-secondary)]/55 hover:text-[var(--text-primary)] border border-transparent' }}">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                                Audit Trails
                            </a>
                        @endif
                    </div>
                </div>
            </nav>

            {{-- User panel --}}
            <div class="px-4 py-4 border-t border-[var(--border-color)] bg-[var(--bg-card)]">
                <div class="flex items-center justify-between gap-3 w-full">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-8 h-8 border border-[var(--border-color)] bg-[var(--bg-secondary)] flex items-center justify-center text-[var(--text-primary)] text-xs font-bold uppercase flex-shrink-0 rounded-full">
                            {{ substr(auth()->user()->name, 0, 2) }}
                        </div>
                        <div class="overflow-hidden">
                            <h4 class="truncate text-sm font-bold text-[var(--text-primary)] leading-tight">{{ auth()->user()->name }}</h4>
                            <span class="text-xs text-[var(--text-secondary)] font-medium leading-none block mt-0.5">{{ auth()->user()->role ? auth()->user()->role->name : 'Staff' }}</span>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="border border-[var(--border-color)] text-[var(--text-primary)] bg-[var(--bg-card)] hover:bg-[var(--bg-secondary)] hover:text-red-600 transition-colors p-1.5 rounded-md" title="Logout" aria-label="Logout">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Main Content Wrap --}}
        <div class="flex flex-1 flex-col overflow-hidden bg-[var(--bg-primary)]">

            {{-- Top Navbar --}}
            <header class="flex h-20 items-center justify-between border-b border-[var(--border-color)] bg-[var(--bg-card)] px-6 z-30 shadow-sm">
                
                {{-- Left: Toggle & Page Title --}}
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="border border-[var(--border-color)] bg-[var(--bg-card)] p-1.5 text-[var(--text-primary)] hover:bg-[var(--bg-secondary)] rounded-md lg:hidden transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                    </button>
                    <div>
                        <h2 class="text-lg font-bold tracking-tight text-[var(--text-primary)]">{{ $title ?? 'Dashboard' }}</h2>
                        @isset($breadcrumb)
                            <div class="text-xs text-[var(--text-secondary)] font-medium mt-0.5">{!! $breadcrumb !!}</div>
                        @endisset
                    </div>
                </div>

                {{-- Right: Branch Switcher & Actions --}}
                <div class="flex items-center gap-4">
                    @livewire('dashboard.branch-selector')

                    {{-- Invert Mode Toggle Button --}}
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

                    @livewire('dashboard.alerts-panel')
                </div>
            </header>

            {{-- Main Scroll Content --}}
            <main class="flex-1 overflow-y-auto bg-[var(--bg-primary)] p-6 lg:p-8">
                
                {{-- Flash Message notifications --}}
                @if (session('success'))
                    <div class="mb-6 flex items-center gap-3 rounded border border-[#edf3ec] bg-[var(--success-bg)] px-4 py-3.5 text-sm font-semibold text-[var(--success)]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 flex items-center gap-3 rounded border border-[#fdebec] bg-[var(--danger-bg)] px-4 py-3.5 text-sm font-semibold text-[var(--danger)]">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>
