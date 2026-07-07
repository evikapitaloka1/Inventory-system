<!DOCTYPE html>
<html lang="id" data-bs-theme="{{ $currentTheme ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Inventaris')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="hero-gradient">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand navbar-brand-gradient fs-4" href="{{ route('landing') }}">
                <i class="bi bi-box-seam-fill" style="-webkit-text-fill-color:#7C3AED;"></i> Inventaris
            </a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <form action="{{ route('theme.toggle') }}" method="POST" class="mb-0">
                    @csrf
                    <input type="hidden" name="theme" value="{{ ($currentTheme ?? 'light') === 'dark' ? 'light' : 'dark' }}">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi {{ ($currentTheme ?? 'light') === 'dark' ? 'bi-sun-fill' : 'bi-moon-stars-fill' }}"></i>
                    </button>
                </form>
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary">Ke Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-sm btn-outline-primary">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-sm btn-primary">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('status'))
        <div class="container mt-3">
            <div class="alert alert-success">{{ session('status') }}</div>
        </div>
    @endif

    @yield('content')

    <footer class="text-center small text-secondary py-4">
        &copy; {{ date('Y') }} Sistem Manajemen Inventaris &mdash; Challenge Seleksi Magang Sistem Informasi
    </footer>
</body>
</html>
