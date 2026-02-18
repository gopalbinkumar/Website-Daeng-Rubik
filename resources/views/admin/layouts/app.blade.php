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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


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
                        customClass: {
                            confirmButton: 'btn btn-primary',
                            cancelButton: 'btn'
                        }
                    });
                });
            </script>
        @endif


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.form-delete').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();

                        Swal.fire({
                            title: 'Yakin ingin menghapus?',
                            text: "Data tidak bisa dikembalikan!",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal',
                            // cancelButtonColor: '#6c757d',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn  btn-primary'
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>

        <script>
            document.addEventListener('submit', function(e) {
                if (e.target.matches('.form-confirm')) {
                    e.preventDefault();

                    let form = e.target;

                    // Tentukan warna berdasarkan teks confirm
                    let confirmClass = 'btn btn-success'; // default hijau
                    let confirmColor = '#28a745';

                    if ((form.dataset.confirm || '').toLowerCase().includes('tolak')) {
                        confirmClass = 'btn btn-danger';
                        confirmColor = '#E53935';
                    }

                    Swal.fire({
                        title: form.dataset.title || 'Yakin?',
                        text: form.dataset.text || '',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: form.dataset.confirm || 'Ya',
                        cancelButtonText: 'Batal',
                        confirmButtonColor: confirmColor,
                        cancelButtonColor: '#6c757d',
                        customClass: {
                            confirmButton: confirmClass,
                            cancelButton: 'btn btn-secondary'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        </script>



    </body>

</html>
