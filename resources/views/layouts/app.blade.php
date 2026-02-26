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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            .swal2-popup {
                background: rgba(17, 24, 39, 0.95) !important;
                backdrop-filter: blur(8px);
                border: 1px solid rgba(255, 255, 255, 0.1);
                border-radius: 1rem !important;
                color: #f3f4f6 !important;
            }
            .swal2-title, .swal2-html-container {
                color: #f3f4f6 !important;
            }
            .swal2-confirm {
                background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
                box-shadow: 0 4px 15px rgba(99, 102, 241, 0.3) !important;
            }
        </style>
    </head>
    <body class="antialiased">

        <!-- Navigation -->
        @include('layouts.navigation')

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 4000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.onmouseenter = Swal.stopTimer;
                        toast.onmouseleave = Swal.resumeTimer;
                    }
                });

                @if(session('success'))
                    Toast.fire({
                        icon: 'success',
                        title: '{{ session('success') }}',
                        background: '#111827',
                        color: '#fff'
                    });
                @endif

                @if(session('error'))
                    Toast.fire({
                        icon: 'error',
                        title: '{{ session('error') }}',
                        background: '#111827',
                        color: '#fff'
                    });
                @endif

                @if(session('warning'))
                    Toast.fire({
                        icon: 'warning',
                        title: '{{ session('warning') }}',
                        background: '#111827',
                        color: '#fff'
                    });
                @endif
            });
        </script>
    </body>
</html>
