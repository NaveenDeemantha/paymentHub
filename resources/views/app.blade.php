<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicons -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/winfield-logo.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/winfield-logo.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/winfield-logo.png') }}">
        <link rel="shortcut icon" href="{{ asset('images/winfield-logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia
    </body>
</html>
