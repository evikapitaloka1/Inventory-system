<?php $__env->startSection('title', 'Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Peminjaman Barang'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="GET" class="d-flex gap-2">
        <select name="status" class="form-select" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <?php $__currentLoopData = ['pending' => 'Menunggu', 'dipinjam' => 'Dipinjam', 'dikembalikan' => 'Dikembalikan', 'ditolak' => 'Ditolak']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php if(request('status') == $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </form>
    <a href="<?php echo e(route('borrowings.create')); ?>" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Ajukan Peminjaman</a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Peminjam</th>
                    <th>Barang</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($borrowing->kode_peminjaman); ?></td>
                        <td><?php echo e($borrowing->nama_peminjam); ?></td>
                        <td>
                            <?php $__currentLoopData = $borrowing->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="small"><?php echo e($detail->product->nama_barang); ?> &times;<?php echo e($detail->jumlah); ?></div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td><?php echo e($borrowing->tanggal_pinjam->translatedFormat('d M Y')); ?></td>
                        <td><?php echo e(optional($borrowing->tanggal_kembali_rencana)->translatedFormat('d M Y') ?? '-'); ?></td>
                        <td><span class="badge <?php echo e($borrowing->statusBadgeClass()); ?>"><?php echo e($borrowing->statusLabel()); ?></span></td>
                        <td class="text-end">
                            <?php if($borrowing->status === 'pending' && auth()->user()->hasRole('admin', 'manager')): ?>
                                <form action="<?php echo e(route('borrowings.approve', $borrowing)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
                                </form>
                                <form action="<?php echo e(route('borrowings.reject', $borrowing)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-x-lg"></i> Tolak</button>
                                </form>
                           <?php elseif($borrowing->status === 'dipinjam' && auth()->user()->hasRole('admin')): ?>
                            <form action="<?php echo e(route('borrowings.return', $borrowing)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Konfirmasi barang sudah dikembalikan?')">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-sm btn-outline-primary"><i class="bi bi-box-arrow-in-down"></i> Kembalikan</button>
                            </form>
                                <span class="text-secondary small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="7" class="text-center text-secondary py-4">Belum ada data peminjaman.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($borrowings->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/borrowings/index.blade.php ENDPATH**/ ?>