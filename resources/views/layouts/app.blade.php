<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KursusKu — Belajar, daftar, dan kelola kursus dalam satu tempat.')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

    @include('partials.navbar')

    @if (session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    <footer class="site-footer">
        <div class="container">
            <p class="footer-copy">&copy; {{ $year ?? date('Y') }} {{ $siteName ?? 'KursusKu' }}. Semua hak dilindungi.</p>
            <p class="footer-meta">Praktikum Pengembangan Aplikasi Web — Arsitektur MVC Laravel</p>
        </div>
    </footer>

</body>
</html>
