<!DOCTYPE html>
<html lang="id" data-bs-theme="<?php echo e($currentTheme ?? 'light'); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Sistem Inventaris'); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="hero-gradient">
    <nav class="navbar navbar-expand-lg py-3">
        <div class="container">
            <a class="navbar-brand navbar-brand-gradient fs-4" href="<?php echo e(route('landing')); ?>">
                <i class="bi bi-box-seam-fill" style="-webkit-text-fill-color:#7C3AED;"></i> Inventaris
            </a>
            <div class="d-flex align-items-center gap-2 ms-auto">
                <form action="<?php echo e(route('theme.toggle')); ?>" method="POST" class="mb-0">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="theme" value="<?php echo e(($currentTheme ?? 'light') === 'dark' ? 'light' : 'dark'); ?>">
                    <button type="submit" class="btn btn-sm btn-outline-secondary">
                        <i class="bi <?php echo e(($currentTheme ?? 'light') === 'dark' ? 'bi-sun-fill' : 'bi-moon-stars-fill'); ?>"></i>
                    </button>
                </form>
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-sm btn-primary">Ke Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-sm btn-outline-primary">Masuk</a>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-sm btn-primary">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <?php if(session('status')): ?>
        <div class="container mt-3">
            <div class="alert alert-success"><?php echo e(session('status')); ?></div>
        </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

    <footer class="text-center small text-secondary py-4">
        &copy; <?php echo e(date('Y')); ?> Sistem Manajemen Inventaris &mdash; Challenge Seleksi Magang Sistem Informasi
    </footer>
</body>
</html>
<?php /**PATH C:\PROJECT\inventaris-app\resources\views/layouts/guest.blade.php ENDPATH**/ ?>