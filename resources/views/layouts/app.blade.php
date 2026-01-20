<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="{{ $description ?? config('app.description', '') }}">
    <title>@yield('title', $title ?? config('app.name', 'Laravel'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v2.1.9/css/unicons.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
   @vite([
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/css/global.css'
    ])

    @stack('styles')
</head>

<body >
    @include('components.navbar')

    <main>

        @yield('content')
    </main>

    @include('components.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- Bootstrap JS is loaded via Vite in app.js, no CDN needed -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        window.Laravel = {!! json_encode([
    'csrfToken' => csrf_token(),
    'baseUrl' => url('/'),
    'locale' => app()->getLocale(),
]           ) !!};
    </script>

    <script>
        // Global error handler for CSRF token issues (419 errors)
        document.addEventListener('DOMContentLoaded', function() {
            // Intercept all fetch requests to handle 419 errors globally
            const originalFetch = window.fetch;
            window.fetch = function(...args) {
                return originalFetch.apply(this, args)
                    .then(response => {
                        if (response.status === 419) {
                            // CSRF token expired, redirect to login
                            window.location.href = '{{ route("login") }}';
                            return Promise.reject(new Error('CSRF token expired'));
                        }
                        return response;
                    });
            };

            // Handle XMLHttpRequest errors (for jQuery AJAX)
            $(document).ajaxError(function(event, xhr, settings) {
                if (xhr.status === 419) {
                    window.location.href = '{{ route("login") }}';
                }
            });

            // Also handle it for forms that might submit directly
            $(document).on('submit', 'form', function(e) {
                // Check if CSRF token exists
                const token = $('meta[name="csrf-token"]').attr('content');
                const formToken = $(this).find('input[name="_token"]').val();

                if (!token && !formToken) {
                    e.preventDefault();
                    window.location.href = '{{ route("login") }}';
                }
            });
        });
    </script>

    @stack('scripts')
</body>

</html>
