<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Admin Panel') - Daeng Rubik</title>
    <meta name="description" content="Admin Panel Daeng Rubik">

    <link rel="icon" href="{{ asset('assets/logo-daeng-rubik.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">

    <link rel="stylesheet" href="{{ asset('assets/admin/admin.css') }}">
    @stack('styles')
</head>
<body class="@yield('body-class')">

<body>
    <div class="admin-layout">
        @include('admin.partials.sidebar')
        
        <div class="admin-main">
            @include('admin.partials.topbar')
            
            <div class="admin-content">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Modal Backdrop -->
    <div id="modalBackdrop" class="modal-backdrop"></div>

    <script src="{{ asset('assets/admin/admin.js') }}" defer></script>
    <script src="{{ asset('assets/admin/events.js') }}" defer></script>
    @stack('scripts')
</body>
</html>
