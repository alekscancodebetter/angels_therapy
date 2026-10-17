<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <meta name="theme-color" content="#4f94bb">
    <title>@yield('title') | Angel Touch Therapy</title>
    @include('favicons')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}?v={{ filemtime(public_path('css/admin.css')) }}">
</head>
<body class="admin-auth-body">
    <main class="admin-auth-shell">
        <section class="admin-auth-brand">
            <a href="{{ route('home') }}"><img src="{{ asset('images/angel-touch-logo.webp') }}" alt="Angel Touch Therapy" width="768" height="512"></a>
            <div>
                <p class="admin-eyebrow">PRIVATE PRACTICE AREA</p>
                <h1>Calm practice management, all in one place.</h1>
                <p>Appointments, customers and enquiries — organised securely for Angel Touch Therapy.</p>
            </div>
        </section>
        <section class="admin-auth-card">
            @yield('content')
        </section>
    </main>
    <script src="{{ asset('js/admin.js') }}?v={{ filemtime(public_path('js/admin.js')) }}" defer></script>
</body>
</html>
