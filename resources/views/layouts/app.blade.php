<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'EasyColoc') }} — @yield('title', 'Dashboard')</title>
        <meta name="description" content="EasyColoc — Smart colocation management platform">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased">

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Flash Messages -->
        <div id="flash-container" style="position:fixed;top:5rem;right:1.5rem;z-index:999;display:flex;flex-direction:column;gap:0.75rem;max-width:400px;">
            @if(session('success'))
                <div class="alert alert-success animate-fade-in" id="flash-success">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div class="alert alert-error animate-fade-in" id="flash-error">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="flex-shrink:0;margin-top:1px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
        </div>

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <script>
            // Auto-dismiss flash messages after 4s
            setTimeout(() => {
                document.querySelectorAll('#flash-success, #flash-error').forEach(el => {
                    el.style.transition = 'opacity 0.4s, transform 0.4s';
                    el.style.opacity = '0';
                    el.style.transform = 'translateX(20px)';
                    setTimeout(() => el.remove(), 400);
                });
            }, 4000);
        </script>
    </body>
</html>
