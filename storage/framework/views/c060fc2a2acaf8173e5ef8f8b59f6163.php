<?php $__env->startSection('title', 'Beranda'); ?>

<?php $__env->startSection('content'); ?>
<section class="container py-5">
    <div class="row align-items-center g-5">
        <div class="col-lg-6">
            <span class="badge-soft rounded-pill px-3 py-2 mb-3 d-inline-block">
                <i class="bi bi-stars me-1"></i> Sistem Manajemen Inventaris Kantor
            </span>
            <h1 class="display-5 fw-bold mb-3">
                Kelola aset & inventaris kantor,
                <span class="navbar-brand-gradient">tanpa lagi manual</span>
            </h1>
            <p class="text-secondary fs-5 mb-4">
                Satu platform untuk mencatat barang, memantau stok, dan mengelola peminjaman
                aset kantor secara real-time &mdash; menggantikan pencatatan manual yang rawan
                kehilangan data, duplikasi, dan laporan yang lambat.
            </p>
            <div class="d-flex gap-2">
                <?php if(auth()->guard()->check()): ?>
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary btn-lg px-4">Buka Dashboard</a>
                <?php else: ?>
                    <a href="<?php echo e(route('register')); ?>" class="btn btn-primary btn-lg px-4">Mulai Sekarang</a>
                    <a href="<?php echo e(route('login')); ?>" class="btn btn-outline-primary btn-lg px-4">Masuk</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="row g-3">
                <div class="col-6">
                    <div class="card stat-card p-4 h-100">
                        <div class="stat-icon mb-2" style="background:rgba(124,58,237,.12); color:#7C3AED;">
                            <i class="bi bi-box2-fill"></i>
                        </div>
                        <div class="fs-3 fw-bold"><?php echo e(number_format($totalBarang)); ?></div>
                        <div class="text-secondary small">Total unit barang</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card stat-card p-4 h-100">
                        <div class="stat-icon mb-2" style="background:rgba(6,182,212,.12); color:#06B6D4;">
                            <i class="bi bi-grid-3x3-gap-fill"></i>
                        </div>
                        <div class="fs-3 fw-bold"><?php echo e($totalJenis); ?></div>
                        <div class="text-secondary small">Jenis barang terdaftar</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card stat-card p-4 h-100">
                        <div class="stat-icon mb-2" style="background:rgba(34,197,94,.12); color:#22C55E;">
                            <i class="bi bi-tags-fill"></i>
                        </div>
                        <div class="fs-3 fw-bold"><?php echo e($totalKategori); ?></div>
                        <div class="text-secondary small">Kategori barang</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card stat-card p-4 h-100">
                        <div class="stat-icon mb-2" style="background:rgba(234,88,12,.12); color:#EA580C;">
                            <i class="bi bi-arrow-left-right"></i>
                        </div>
                        <div class="fs-3 fw-bold">Real-time</div>
                        <div class="text-secondary small">Status peminjaman</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h2 class="fw-bold mb-1">Barang Tersedia untuk Dipinjam</h2>
            <p class="text-secondary mb-0">Contoh aset kantor yang saat ini siap untuk diajukan peminjamannya.</p>
        </div>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(route('borrowings.create')); ?>" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> Ajukan Peminjaman
            </a>
        <?php endif; ?>
    </div>

    <div class="row g-4">
        <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <img src="<?php echo e($product->imageUrl()); ?>" class="product-thumb" alt="<?php echo e($product->nama_barang); ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <h5 class="card-title mb-1"><?php echo e($product->nama_barang); ?></h5>
                            <span class="badge text-bg-success">Tersedia</span>
                        </div>
                        <p class="text-secondary small mb-2"><?php echo e($product->category->name); ?> &middot; <?php echo e($product->kode_barang); ?></p>
                        <div class="d-flex justify-content-between small text-secondary">
                            <span><i class="bi bi-geo-alt me-1"></i><?php echo e($product->lokasi_penyimpanan ?? '-'); ?></span>
                            <span><i class="bi bi-boxes me-1"></i>Stok: <?php echo e($product->stok); ?></span>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-12">
                <div class="alert alert-secondary">Belum ada barang yang tersedia saat ini.</div>
            </div>
        <?php endif; ?>
    </div>
</section>

<section class="container py-5">
    <div class="row g-4 text-center">
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <i class="bi bi-clipboard-check fs-1 mb-2" style="color:#7C3AED;"></i>
                <h5>Pencatatan Akurat</h5>
                <p class="text-secondary small mb-0">Setiap barang punya kode unik, kategori, lokasi, dan riwayat kondisi yang jelas.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <i class="bi bi-graph-up-arrow fs-1 mb-2" style="color:#06B6D4;"></i>
                <h5>Laporan Instan</h5>
                <p class="text-secondary small mb-0">Export laporan stok ke PDF & Excel kapan saja, tanpa rekap manual.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 h-100">
                <i class="bi bi-shield-lock fs-1 mb-2" style="color:#22C55E;"></i>
                <h5>Akses Berlapis</h5>
                <p class="text-secondary small mb-0">Role Admin, Staff, dan Manager dengan hak akses yang sesuai tanggung jawab.</p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/welcome.blade.php ENDPATH**/ ?>