<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Daeng Rubik')</title>
    <meta name="description" content="Daeng Rubik - rubik store, event rubik, dan materi pembelajaran rubik.">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('assets/logo-daeng-rubik.png') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,600,700,800,900&display=swap" rel="stylesheet" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.5.1/css/all.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                    showConfirmButton: false,
                    timer: 1500,
                });
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#E53935'
                });
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: "{{ $errors->first() }}",
                    confirmButtonColor: '#E53935',
                    cancelButtonColor: '#fff',
                    customClass: {
                        confirmButton: 'btn btn-primary',
                        cancelButton: 'btn btn-secondary'
                    }
                });
            });
        </script>
    @endif


    <script>
        function showLoginAlert() {
            Swal.fire({
                icon: 'warning',
                title: 'Login Diperlukan',
                text: 'Anda harus login terlebih dahulu',
                confirmButtonText: 'Login Sekarang',
                confirmButtonColor: '#E53935',
                cancelButtonColor: '#6c757d',
                customClass: {
                    confirmButton: 'btn'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "{{ route('auth.login') }}";
                }
            });
        }
    </script>


</body>

</html>
