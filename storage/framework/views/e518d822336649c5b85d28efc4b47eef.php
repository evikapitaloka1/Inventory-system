<!DOCTYPE html>
<html lang="id" data-bs-theme="<?php echo e($currentTheme ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <aside class="sidebar p-3" id="appSidebar">
            <a href="<?php echo e(route('dashboard')); ?>" class="d-flex align-items-center gap-2 text-decoration-none mb-4 px-2">
                <i class="bi bi-box-seam-fill fs-4" style="color:#7C3AED"></i>
                <span class="fs-5 navbar-brand-gradient">Inventaris</span>
            </a>

            <nav class="nav flex-column">
                <a href="<?php echo e(route('dashboard')); ?>" class="nav-link <?php echo e(request()->routeIs('dashboard') ? 'active' : ''); ?>">
                    <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard
                </a>
                <a href="<?php echo e(route('products.index')); ?>" class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>">
                    <i class="bi bi-box2-fill me-2"></i> Master Barang
                </a>
                <a href="<?php echo e(route('categories.index')); ?>" class="nav-link <?php echo e(request()->routeIs('categories.*') ? 'active' : ''); ?>">
                    <i class="bi bi-tags-fill me-2"></i> Kategori
                </a>
                <a href="<?php echo e(route('borrowings.index')); ?>" class="nav-link <?php echo e(request()->routeIs('borrowings.*') ? 'active' : ''); ?>">
                    <i class="bi bi-arrow-left-right me-2"></i> Peminjaman
                </a>
                <a href="<?php echo e(route('reports.index')); ?>" class="nav-link <?php echo e(request()->routeIs('reports.*') ? 'active' : ''); ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill me-2"></i> Laporan
                </a>
                <a href="<?php echo e(route('landing')); ?>" class="nav-link">
                    <i class="bi bi-house-door-fill me-2"></i> Lihat Landing Page
                </a>
            </nav>

            <div class="mt-auto pt-4 px-2 small text-secondary">
                <div class="fw-semibold"><?php echo e(auth()->user()->name); ?></div>
                <div class="badge-soft rounded-pill px-2 py-1 mt-1 d-inline-block text-capitalize">
                    <?php echo e(auth()->user()->role->label ?? '-'); ?>

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
                    <h5 class="mb-0"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h5>
                </div>

                <div class="d-flex align-items-center gap-2">
                    <form action="<?php echo e(route('theme.toggle')); ?>" method="POST" class="mb-0">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="theme" value="<?php echo e(($currentTheme ?? 'light') === 'dark' ? 'light' : 'dark'); ?>">
                        <button type="submit" class="btn btn-sm btn-outline-secondary" title="Ganti tampilan">
                            <i class="bi <?php echo e(($currentTheme ?? 'light') === 'dark' ? 'bi-sun-fill' : 'bi-moon-stars-fill'); ?>"></i>
                        </button>
                    </form>

                    <div class="dropdown">
                        <button class="btn btn-sm btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> <?php echo e(Str::limit(auth()->user()->name, 14)); ?>

                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="<?php echo e(route('logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
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
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i><?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\PROJECT\inventaris-app\resources\views/layouts/app.blade.php ENDPATH**/ ?>