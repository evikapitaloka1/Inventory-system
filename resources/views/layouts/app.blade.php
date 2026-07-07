<!DOCTYPE html>
<html lang="id" data-bs-theme="{{ $currentTheme ?? 'light' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar p-3" id="appSidebar">
            <a href="{{ route('dashboard') }}" class="d-flex align-items-center gap-2 text-decoration-none mb-4 px-2">
                <i class="bi bi-box-seam-fill fs-4" style="color:#7C3AED"></i>
                <span class="fs-5 navbar-brand-gradient">Inventaris</span>
            </a>

            <nav class="nav flex-column">
                <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                </a>
                <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box2-fill me-2"></i> Master Barang
                </a>
                <a href="{{ route('categories.index') }}" class="nav-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tags-fill me-2"></i> Kategori
                </a>
                <a href="{{ route('borrowings.index') }}" class="nav-link {{ request()->routeIs('borrowings.*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right me-2"></i> Peminjaman
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Laporan
                </a>
                <a href="{{ route('landing') }}" class="nav-link">
                    <i class="bi bi-house-door-fill me-2"></i> Lihat Landing Page
                </a>
            </nav>

            <div class="mt-auto pt-4 px-2 small text-secondary">
                <div class="fw-semibold">{{ auth()->user()->name }}</div>
                <div class="badge-soft rounded-pill px-2 py-1 mt-1 d-inline-block text-capitalize">
                    {{ auth()->user()->role->label ?? '-' }}
                </div>
            </div>
        </aside>

        <!-- Main content -->
        <div class="flex-fill" style="min-width:0;">
            <header class="topbar d-flex align-items-center justify-content-between px-3 px-md-4 py-3 sticky-top">
                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-sm btn-outline-secondary d-lg-none" id="sidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>
                    <h5 class="mb-0">@yield('page-title', 'Dashboard')</h5>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <form action="{{ route('theme.toggle') }}" method="POST" class="mb-0">
                        @csrf
                        <input type="hidden" name="theme" value="{{ ($currentTheme ?? 'light') === 'dark' ? 'light' : 'dark' }}">
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Ganti tampilan">
                            <i class="bi {{ ($currentTheme ?? 'light') === 'dark' ? 'bi-sun-fill' : 'bi-moon-stars-fill' }}"></i>
                        </button>
                    </form>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Str::limit(auth()->user()->name, 14) }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger" type="submit">
                                        <i class="bi bi-box-arrow-right me-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            <main class="p-3 p-md-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
