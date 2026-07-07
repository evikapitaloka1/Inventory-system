<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>

<?php if($pendingApprovals > 0 && auth()->user()->hasRole('admin', 'manager')): ?>
    <div class="alert alert-warning d-flex justify-content-between align-items-center">
        <div><i class="bi bi-hourglass-split me-2"></i>Ada <strong><?php echo e($pendingApprovals); ?></strong> pengajuan peminjaman menunggu persetujuan.</div>
        <a href="<?php echo e(route('borrowings.index', ['status' => 'pending'])); ?>" class="btn btn-sm btn-warning">Lihat</a>
    </div>
<?php endif; ?>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(124,58,237,.12); color:#7C3AED;"><i class="bi bi-box2-fill"></i></div>
            <div class="fs-3 fw-bold"><?php echo e(number_format($totalBarang)); ?></div>
            <div class="text-secondary small">Total Barang (unit)</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(234,88,12,.12); color:#EA580C;"><i class="bi bi-arrow-left-right"></i></div>
            <div class="fs-3 fw-bold"><?php echo e(number_format($barangDipinjam)); ?></div>
            <div class="text-secondary small">Barang Dipinjam</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(34,197,94,.12); color:#22C55E;"><i class="bi bi-check2-circle"></i></div>
            <div class="fs-3 fw-bold"><?php echo e(number_format($barangTersedia)); ?></div>
            <div class="text-secondary small">Barang Tersedia</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 h-100">
            <div class="stat-icon mb-2" style="background:rgba(6,182,212,.12); color:#06B6D4;"><i class="bi bi-grid-3x3-gap-fill"></i></div>
            <div class="fs-3 fw-bold"><?php echo e($totalJenisBarang); ?></div>
            <div class="text-secondary small">Jenis Barang</div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card p-3 h-100">
            <h6 class="mb-3">Grafik Peminjaman per Bulan</h6>
            <canvas id="borrowChart" height="110"></canvas>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card p-3 h-100">
            <h6 class="mb-3"><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i>Stok Menipis</h6>
            <?php $__empty_1 = true; $__currentLoopData = $lowStockProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="d-flex justify-content-between align-items-center py-2 <?php echo e(!$loop->last ? 'border-bottom' : ''); ?>">
                    <div>
                        <div class="fw-semibold small"><?php echo e($product->nama_barang); ?></div>
                        <div class="text-secondary" style="font-size:.75rem;"><?php echo e($product->kode_barang); ?></div>
                    </div>
                    <span class="badge text-bg-danger"><?php echo e($product->stok); ?></span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-secondary small mb-0">Semua stok dalam batas aman.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="card p-3 mt-3">
    <h6 class="mb-3">Peminjaman Terbaru</h6>
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tanggal Pinjam</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentBorrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($borrowing->kode_peminjaman); ?></td>
                        <td><?php echo e($borrowing->nama_peminjam); ?></td>
                        <td><?php echo e($borrowing->details->pluck('product.nama_barang')->join(', ')); ?></td>
                        <td><?php echo e($borrowing->tanggal_pinjam->translatedFormat('d M Y')); ?></td>
                        <td><span class="badge <?php echo e($borrowing->statusBadgeClass()); ?>"><?php echo e($borrowing->statusLabel()); ?></span></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-secondary py-3">Belum ada data peminjaman.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('borrowChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($chartLabels, 15, 512) ?>,
            datasets: [{
                label: 'Jumlah Peminjaman',
                data: <?php echo json_encode($chartData, 15, 512) ?>,
                borderColor: '#7C3AED',
                backgroundColor: 'rgba(124,58,237,.15)',
                fill: true,
                tension: 0.35,
                pointBackgroundColor: '#06B6D4',
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } }
        }
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/dashboard.blade.php ENDPATH**/ ?>