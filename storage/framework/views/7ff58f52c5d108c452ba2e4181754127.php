<?php $__env->startSection('title', 'Laporan'); ?>
<?php $__env->startSection('page-title', 'Laporan'); ?>

<?php $__env->startSection('content'); ?>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card p-4 h-100">
            <div class="stat-icon mb-3" style="background:rgba(124,58,237,.12); color:#7C3AED;">
                <i class="bi bi-box2-fill"></i>
            </div>
            <h5>Laporan Data Barang</h5>
            <p class="text-secondary small mb-4">Daftar seluruh barang beserta kategori, stok, lokasi penyimpanan, dan kondisinya.</p>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('reports.products.pdf')); ?>" class="btn btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh PDF
                </a>
                <a href="<?php echo e(route('reports.products.excel')); ?>" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Unduh Excel
                </a>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card p-4 h-100">
            <div class="stat-icon mb-3" style="background:rgba(6,182,212,.12); color:#06B6D4;">
                <i class="bi bi-arrow-left-right"></i>
            </div>
            <h5>Laporan Peminjaman</h5>
            <p class="text-secondary small mb-4">Riwayat seluruh transaksi peminjaman barang: peminjam, tanggal, dan status.</p>
            <div class="d-flex gap-2">
                <a href="<?php echo e(route('reports.borrowings.pdf')); ?>" class="btn btn-outline-danger">
                    <i class="bi bi-file-earmark-pdf-fill me-1"></i> Unduh PDF
                </a>
                <a href="<?php echo e(route('reports.borrowings.excel')); ?>" class="btn btn-outline-success">
                    <i class="bi bi-file-earmark-excel-fill me-1"></i> Unduh Excel
                </a>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/reports/index.blade.php ENDPATH**/ ?>