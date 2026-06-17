<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Dashboard' }} — E-DN</title>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    <style>
        :root {
            --bg-primary: #0f0f1a;
            --bg-card: #1a1a2e;
            --bg-input: #12122a;
            --border-color: #2d2d44;
            --accent-primary: #6c5ce7;
            --accent-success: #00b894;
            --accent-warning: #fdcb6e;
            --accent-danger: #e17055;
            --text-heading: #ffffff;
            --text-body: #a0a0b8;
            --sidebar-width: 260px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-primary);
            color: var(--text-body);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-card);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            z-index: 100;
            transition: transform 0.3s cubic-bezier(.4,0,.2,1);
        }

        .sidebar-brand {
            padding: 28px 24px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-brand h1 {
            font-size: 1.75rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe, #fd79a8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: -0.5px;
        }

        .sidebar-brand span {
            display: block;
            font-size: 0.7rem;
            color: var(--text-body);
            margin-top: 2px;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            -webkit-text-fill-color: var(--text-body);
        }

        .sidebar-nav {
            flex: 1;
            padding: 16px 12px;
            overflow-y: auto;
        }

        .sidebar-nav .nav-label {
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: #5a5a7a;
            padding: 12px 12px 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            border-radius: 10px;
            color: var(--text-body);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .nav-item:hover {
            background: rgba(108, 92, 231, 0.08);
            color: var(--text-heading);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(108,92,231,0.15), rgba(108,92,231,0.05));
            color: var(--accent-primary);
            box-shadow: inset 3px 0 0 var(--accent-primary);
        }

        .nav-item .nav-icon {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            opacity: 0.7;
        }

        .nav-item.active .nav-icon { opacity: 1; }

        .sidebar-user {
            padding: 16px 16px 20px;
            border-top: 1px solid var(--border-color);
        }

        .sidebar-user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .sidebar-user-avatar {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 700;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .sidebar-user-name {
            font-size: 0.85rem;
            color: var(--text-heading);
            font-weight: 600;
        }

        .sidebar-user-role {
            display: inline-block;
            margin-top: 2px;
            font-size: 0.65rem;
            padding: 2px 8px;
            border-radius: 20px;
            background: rgba(108,92,231,0.15);
            color: var(--accent-primary);
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .sidebar-user-role.superadmin {
            background: rgba(253,203,110,0.15);
            color: #fdcb6e;
        }

        .btn-logout {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            background: transparent;
            color: var(--text-body);
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .btn-logout:hover {
            border-color: var(--accent-danger);
            color: var(--accent-danger);
            background: rgba(225,112,85,0.08);
        }

        /* ── Mobile Top Bar ── */
        .mobile-topbar {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            padding: 0 16px;
            align-items: center;
            justify-content: space-between;
            z-index: 90;
        }

        .mobile-topbar button {
            background: none;
            border: none;
            color: var(--text-heading);
            cursor: pointer;
            padding: 8px;
        }

        .mobile-topbar .brand {
            font-size: 1.25rem;
            font-weight: 700;
            background: linear-gradient(135deg, #6c5ce7, #a29bfe);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .notif-bell {
            position: relative;
        }

        .notif-bell .badge {
            position: absolute;
            top: 2px;
            right: 2px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--accent-danger);
        }

        /* ── Sidebar Overlay ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 99;
        }

        /* ── Main Content ── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin 0.3s ease;
        }

        .content-topbar {
            position: sticky;
            top: 0;
            background: rgba(15,15,26,0.85);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 20px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 50;
        }

        .content-topbar h2 {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--text-heading);
        }

        .breadcrumb {
            font-size: 0.75rem;
            color: var(--text-body);
            margin-top: 4px;
        }

        .breadcrumb a {
            color: var(--accent-primary);
            text-decoration: none;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .notif-count-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            background: rgba(108,92,231,0.12);
            border: 1px solid rgba(108,92,231,0.2);
            border-radius: 20px;
            color: var(--accent-primary);
            font-size: 0.78rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .notif-count-badge:hover {
            background: rgba(108,92,231,0.2);
        }

        .content-body {
            padding: 28px 32px 100px;
        }

        /* ── Mobile Bottom Nav ── */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 68px;
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            z-index: 90;
            padding: 0 8px;
            align-items: center;
            justify-content: space-around;
        }

        .bottom-nav-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
            text-decoration: none;
            color: var(--text-body);
            font-size: 0.6rem;
            font-weight: 500;
            padding: 8px 12px;
            border-radius: 12px;
            transition: all 0.2s ease;
        }

        .bottom-nav-item.active {
            color: var(--accent-primary);
            background: rgba(108,92,231,0.1);
        }

        .bottom-nav-item svg {
            width: 22px;
            height: 22px;
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

        .toast-success {
            background: linear-gradient(135deg, #00b894, #00a381);
            border: 1px solid rgba(0,184,148,0.3);
        }

        .toast-error {
            background: linear-gradient(135deg, #e17055, #d63031);
            border: 1px solid rgba(225,112,85,0.3);
        }

        @keyframes toastSlideIn {
            from { transform: translateX(100px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }

        /* ── Responsive ── */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .mobile-topbar {
                display: flex;
            }

            .main-content {
                margin-left: 0;
                padding-top: 60px;
            }

            .content-topbar {
                display: none;
            }

            .content-body {
                padding: 20px 16px 100px;
            }

            .mobile-bottom-nav {
                display: flex;
            }
        }
    </style>
</head>
<body x-data="{ sidebarOpen: false }">

    {{-- ── Flash Toasts ── --}}
    <div class="toast-container">
        @if (session('success'))
            <div class="toast toast-success"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave.duration.300ms>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4"/><circle cx="12" cy="12" r="10"/></svg>
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="toast toast-error"
                 x-data="{ show: true }"
                 x-show="show"
                 x-init="setTimeout(() => show = false, 4000)"
                 x-transition:leave.duration.300ms>
                <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M15 9l-6 6M9 9l6 6"/></svg>
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- ── Mobile Top Bar ── --}}
    <header class="mobile-topbar">
        <button @click="sidebarOpen = true" aria-label="Open menu">
            <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 12h18M3 6h18M3 18h18"/></svg>
        </button>
        <span class="brand">HMS</span>
        <div style="width: 40px;"></div> {{-- Spacer to balance open menu button --}}
    </header>

    {{-- ── Sidebar Overlay ── --}}
    <div class="sidebar-overlay"
         :class="{ 'open': sidebarOpen }"
         @click="sidebarOpen = false"></div>

    {{-- ── Sidebar ── --}}
    <aside class="sidebar" :class="{ 'open': sidebarOpen }">
        <div class="sidebar-brand">
            <h1>HMS</h1>
            <span>Hotel Management</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main Menu</div>

            <a href="{{ route('dashboard') }}"
               class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
               @click="sidebarOpen = false">
                <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="9" rx="1"/><rect x="14" y="3" width="7" height="5" rx="1"/><rect x="14" y="12" width="7" height="9" rx="1"/><rect x="3" y="16" width="7" height="5" rx="1"/></svg>
                Room Board
            </a>

            @if(auth()->user()->isAdmin() || auth()->user()->isFrontDesk())
                <a href="{{ route('bookings') }}"
                   class="nav-item {{ request()->routeIs('bookings') ? 'active' : '' }}"
                   @click="sidebarOpen = false">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Registry
                </a>

                <a href="{{ route('guest-bills') }}"
                   class="nav-item {{ request()->routeIs('guest-bills') ? 'active' : '' }}"
                   @click="sidebarOpen = false">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Billing
                </a>
            @endif

            @if(auth()->user()->isAdmin())
                <div class="nav-label" style="margin-top: 12px;">Admin</div>

                <a href="{{ route('rooms') }}"
                   class="nav-item {{ request()->routeIs('rooms') ? 'active' : '' }}"
                   @click="sidebarOpen = false">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                    Room Config
                </a>

                <a href="{{ route('admin.users') }}"
                   class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}"
                   @click="sidebarOpen = false">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                    User Management
                </a>

                <a href="{{ route('admin.services') }}"
                   class="nav-item {{ request()->routeIs('admin.services') ? 'active' : '' }}"
                   @click="sidebarOpen = false">
                    <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
                    Services
                </a>
            @endif
        </nav>

        {{-- ── User Info ── --}}
        <div class="sidebar-user">
            <div class="sidebar-user-info">
                <div class="sidebar-user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                    <span class="sidebar-user-role {{ auth()->user()->isSuperAdmin() ? 'superadmin' : '' }}">{{ auth()->user()->role ? auth()->user()->role->name : 'Staff' }}</span>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Content ── --}}
    <main class="main-content">
        <div class="content-topbar">
            <div>
                <h2>{{ $title ?? 'Dashboard' }}</h2>
                @isset($breadcrumb)
                    <div class="breadcrumb">{!! $breadcrumb !!}</div>
                @endisset
            </div>
            <div class="topbar-right">
                {{-- Plain placeholder topbar right --}}
            </div>
        </div>

        <div class="content-body">
            {{ $slot }}
        </div>
    </main>

    {{-- ── Mobile Bottom Nav ── --}}
    <nav class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}"
           class="bottom-nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Rooms
        </a>
        @if(auth()->user()->isAdmin() || auth()->user()->isFrontDesk())
            <a href="{{ route('bookings') }}"
               class="bottom-nav-item {{ request()->routeIs('bookings') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Bookings
            </a>
            <a href="{{ route('guest-bills') }}"
               class="bottom-nav-item {{ request()->routeIs('guest-bills') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Bills
            </a>
        @endif
        @if(auth()->user()->isAdmin())
            <a href="{{ route('rooms') }}"
               class="bottom-nav-item {{ request()->routeIs('rooms') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><circle cx="12" cy="12" r="3"/></svg>
                Config
            </a>
            <a href="{{ route('admin.users') }}"
               class="bottom-nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
                Users
            </a>
            <a href="{{ route('admin.services') }}"
               class="bottom-nav-item {{ request()->routeIs('admin.services') ? 'active' : '' }}">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
                Services
            </a>
        @endif
    </nav>

    @livewireScripts
</body>
</html>
