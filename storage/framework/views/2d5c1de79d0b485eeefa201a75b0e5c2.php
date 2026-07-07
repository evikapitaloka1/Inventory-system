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
                        <td>
                            <span class="badge <?php echo e($borrowing->statusBadgeClass()); ?>"><?php echo e($borrowing->statusLabel()); ?></span>

                            <?php if($borrowing->status === 'ditolak' && $borrowing->alasan_penolakan): ?>
                                <div>
                                    <button type="button" class="btn btn-link btn-sm p-0 text-danger" data-bs-toggle="modal" data-bs-target="#alasanModal<?php echo e($borrowing->id); ?>">
                                        <i class="bi bi-info-circle"></i> Lihat alasan
                                    </button>
                                </div>
                            <?php endif; ?>

                            <?php if($borrowing->status === 'dikembalikan' && ($borrowing->catatan_pengembalian || $borrowing->foto_pengembalian)): ?>
                                <div>
                                    <button type="button" class="btn btn-link btn-sm p-0" data-bs-toggle="modal" data-bs-target="#detailKembaliModal<?php echo e($borrowing->id); ?>">
                                        <i class="bi bi-image"></i> Lihat detail
                                    </button>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="text-end">
                            <?php if($borrowing->status === 'pending' && auth()->user()->hasRole('admin', 'manager')): ?>
                                <form action="<?php echo e(route('borrowings.approve', $borrowing)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-sm btn-success"><i class="bi bi-check-lg"></i> Setujui</button>
                                </form>
                                <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#tolakModal<?php echo e($borrowing->id); ?>">
                                    <i class="bi bi-x-lg"></i> Tolak
                                </button>
                            <?php elseif($borrowing->status === 'dipinjam' && auth()->user()->hasRole('admin')): ?>
                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#kembaliModal<?php echo e($borrowing->id); ?>">
                                    <i class="bi bi-box-arrow-in-down"></i> Kembalikan
                                </button>
                            <?php else: ?>
                                <span class="text-secondary small">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>

                    <?php if($borrowing->status === 'pending'): ?>
                        <div class="modal fade" id="tolakModal<?php echo e($borrowing->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('borrowings.reject', $borrowing)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Tolak Peminjaman <?php echo e($borrowing->kode_peminjaman); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <label class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                                            <textarea name="alasan_penolakan" class="form-control" rows="3" required
                                                placeholder="Contoh: Barang sedang dalam perbaikan / stok dibutuhkan untuk keperluan lain."></textarea>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Tolak Peminjaman</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($borrowing->status === 'ditolak' && $borrowing->alasan_penolakan): ?>
                        <div class="modal fade" id="alasanModal<?php echo e($borrowing->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Penolakan &mdash; <?php echo e($borrowing->kode_peminjaman); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p class="mb-1 small text-secondary">Ditolak oleh: <?php echo e($borrowing->approver->name ?? '-'); ?></p>
                                        <p class="mb-0"><?php echo e($borrowing->alasan_penolakan); ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($borrowing->status === 'dipinjam'): ?>
                        <div class="modal fade" id="kembaliModal<?php echo e($borrowing->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="<?php echo e(route('borrowings.return', $borrowing)); ?>" enctype="multipart/form-data">
                                        <?php echo csrf_field(); ?>
                                        <div class="modal-header">
                                            <h5 class="modal-title">Konfirmasi Pengembalian &mdash; <?php echo e($borrowing->kode_peminjaman); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Kondisi Barang Saat Kembali <span class="text-danger">*</span></label>
                                                <select name="kondisi_saat_kembali" class="form-select" required>
                                                    <option value="baik">Baik</option>
                                                    <option value="rusak_ringan">Rusak Ringan</option>
                                                    <option value="rusak_berat">Rusak Berat</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Foto Kondisi Barang <span class="text-secondary small">(opsional)</span></label>
                                                <input type="file" name="foto_pengembalian" class="form-control" accept="image/*">
                                            </div>
                                            <div class="mb-1">
                                                <label class="form-label">Catatan <span class="text-secondary small">(opsional)</span></label>
                                                <textarea name="catatan_pengembalian" class="form-control" rows="2" placeholder="Contoh: ada goresan kecil di casing."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Konfirmasi Pengembalian</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if($borrowing->status === 'dikembalikan' && ($borrowing->catatan_pengembalian || $borrowing->foto_pengembalian)): ?>
                        <div class="modal fade" id="detailKembaliModal<?php echo e($borrowing->id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Detail Pengembalian &mdash; <?php echo e($borrowing->kode_peminjaman); ?></h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <?php if($borrowing->fotoPengembalianUrl()): ?>
                                            <img src="<?php echo e($borrowing->fotoPengembalianUrl()); ?>" class="img-fluid rounded mb-3" alt="Foto pengembalian">
                                        <?php endif; ?>
                                        <?php if($borrowing->catatan_pengembalian): ?>
                                            <p class="mb-0"><?php echo e($borrowing->catatan_pengembalian); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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