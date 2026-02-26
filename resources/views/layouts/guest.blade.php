<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'EasyColoc') }}</title>
        <meta name="description" content="Sign in to EasyColoc — Colocation management made easy">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { background: var(--bg-dark); }
        </style>
    </head>
    <body class="antialiased">

        <!-- Animated background blobs -->
        <div style="position:fixed;inset:0;overflow:hidden;pointer-events:none;z-index:0;">
            <div class="float-blob" style="width:500px;height:500px;top:-100px;left:-100px;background:radial-gradient(circle,#6366f1,transparent);animation-duration:10s;"></div>
            <div class="float-blob" style="width:400px;height:400px;bottom:-80px;right:-80px;background:radial-gradient(circle,#06b6d4,transparent);animation-duration:13s;animation-delay:3s;"></div>
        </div>

        <div class="auth-wrapper" style="position:relative;z-index:1;">
            {{ $slot }}
        </div>
    </body>
</html>
