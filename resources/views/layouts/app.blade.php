<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'My Application')</title>

    {{-- CSRF Token (WAJIB kalau ada AJAX) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Bootstrap (optional, buat alert style) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container">

        {{-- ✅ Alert Error --}}
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        {{-- ✅ Konten --}}
        @yield('content')

    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 🔥 SweetAlert (optional, lebih modern) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if(session('error'))
    <script>
        Swal.fire({
            icon: 'warning',
            title: 'Session Expired',
            text: '{{ session('error') }}',
            confirmButtonText: 'OK'
        });
    </script>
    @endif

    {{-- 🔥 Auto attach CSRF ke axios (kalau dipakai) --}}
    <script>
        if (window.axios) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }
    </script>

</body>
</html>