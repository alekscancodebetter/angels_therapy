<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#4f94bb">
    <title>@yield('title', 'Admin') | Angel Touch Therapy</title>
    <link rel="icon" type="image/png" href="{{ asset('images/angel-touch-mark.png') }}">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body class="admin-body">
    <aside class="admin-sidebar" id="admin-sidebar" data-admin-sidebar>
        <a class="admin-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('images/angel-touch-mark.png') }}" alt="" width="1200" height="620">
            <span><strong>Angel Touch</strong><small>Practice admin</small></span>
        </a>

        <nav class="admin-nav" aria-label="Admin navigation">
            <p>Practice</p>
            <a href="{{ route('admin.dashboard') }}" @class(['is-active' => request()->routeIs('admin.dashboard')])><span>⌂</span>Overview</a>
            <a href="{{ route('admin.calendar') }}" @class(['is-active' => request()->routeIs('admin.calendar', 'admin.appointments.*')])><span>□</span>Calendar</a>
            <a href="{{ route('admin.customers.index') }}" @class(['is-active' => request()->routeIs('admin.customers.*')])><span>○</span>Customers</a>
            <p>Account</p>
            <a href="{{ route('admin.settings.edit') }}" @class(['is-active' => request()->routeIs('admin.settings.*')])><span>◇</span>Settings</a>
            <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer"><span>↗</span>View website</a>
        </nav>

        <div class="admin-profile">
            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
            <div><strong>{{ auth()->user()->name }}</strong><small>{{ auth()->user()->email }}</small></div>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" title="Sign out" aria-label="Sign out">↗</button>
            </form>
        </div>
    </aside>

    <div class="admin-overlay" data-admin-overlay></div>

    <main class="admin-main">
        <header class="admin-topbar">
            <button class="admin-menu" type="button" aria-controls="admin-sidebar" aria-expanded="false" data-admin-menu><span></span><span></span><span></span><b>Menu</b></button>
            <div>
                <p>{{ now()->format('l, j F Y') }}</p>
                <strong>Welcome back, {{ explode(' ', auth()->user()->name)[0] }}.</strong>
            </div>
            <a class="admin-quick-action" href="{{ route('admin.appointments.create') }}">+ New appointment</a>
        </header>

        @if (session('status'))
            <div class="admin-notice" role="status">{{ session('status') }}</div>
        @endif

        <div class="admin-content">
            @yield('content')
        </div>
    </main>

    <script src="{{ asset('js/admin.js') }}" defer></script>
</body>
</html>
