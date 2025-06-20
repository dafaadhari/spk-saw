    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="horizontal">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Sistem Pendukung Keputusan') }}</title>

        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('template-dash/assets/images/logo.webp') }}" />

        <!-- Color modes -->
        <script src="{{ asset('template-dash/assets/js/color-modes.js') }}"></script>

        <!-- Libs CSS -->
        <link rel="stylesheet" href="{{ asset('template-dash/assets/libs/theme.min.css') }}">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/simplebar@6.2.5/dist/simplebar.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Tanpa Vite, pakai asset() -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="{{ asset('js/app.js') }}" defer></script>

        @livewireStyles
    </head>

    <body>
        {{-- Modal Konfirmasi Global (opsional) --}}
        @stack('modals')

        {{-- Main Wrapper --}}
        <main id="main-wrapper" class="main-wrapper">
            {{-- Navbar dan Header Template --}}
            @include('layouts.header')
            @include('layouts.navbar')

            {{-- Konten Dinamis --}}
            @yield('content')

        </main>

        {{-- JS Libraries --}}
        <script src="{{ asset('template-dash/assets/js/bootstrap.bundle.min.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/feather.min.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/simplebar.min.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/theme.min.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/jsvectormap.min.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/world.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/apexcharts.min.js') }}"></script>
        <script src="{{ asset('template-dash/assets/js/chart.js') }}"></script>

        {{-- Feather icons --}}
        <script>
            feather.replace();
        </script>

        @livewireScripts
    </body>

    </html>