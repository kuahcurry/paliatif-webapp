<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Ruang Hening') }}</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">

        <style>[x-cloak] { display: none !important; }</style>

        @stack('head')

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="{{ $bodyClass }}">
        <div class="{{ $pageClass }}">
            @unless($hideNavigation)
                @include('layouts.navigation')
            @endunless

            <!-- Page Heading -->
            @if (! $hideHeader && isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
