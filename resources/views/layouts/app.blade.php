<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Daeng Rubik')</title>
    <meta name="description" content="Daeng Rubik - rubik store, event rubik, dan materi pembelajaran rubik.">

    <link rel="icon" href="{{ asset('assets/logo-daeng-rubik.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />

    <!-- Base CSS (always loaded) -->
    <link rel="stylesheet" href="{{ asset('assets/css/base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    
    <!-- Page-specific CSS -->
    @stack('styles')
</head>
<body>
    @include('partials.navbar')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('assets/daengrubik.js') }}" defer></script>
</body>
</html>

