<?php $__env->startSection('title', 'Master Barang'); ?>
<?php $__env->startSection('page-title', 'Master Data Barang'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="Cari nama, kode, atau lokasi..." style="min-width:240px;">
        <select name="category_id" class="form-select" style="min-width:180px;">
            <option value="">Semua Kategori</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(request('category_id') == $category->id): echo 'selected'; endif; ?>><?php echo e($category->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </form>

    <?php if(auth()->user()->hasRole('admin', 'staff')): ?>
        <a href="<?php echo e(route('products.create')); ?>" class="btn btn-primary">
            <i class="bi bi-plus-lg me-1"></i> Tambah Barang
        </a>
    <?php endif; ?>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Gambar</th> <th>Nama Barang</th>
                    <th>Kategori</th>
                    <th>Stok</th>
                    <th>Lokasi</th>
                    <th>Kondisi</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td><?php echo e($product->kode_barang); ?></td>
                        
                        <td>
                            <?php if($product->gambar): ?>
                                <img src="<?php echo e(asset('storage/' . $product->gambar)); ?>" alt="<?php echo e($product->nama_barang); ?>" class="img-thumbnail" style="width: 60px; height: 60px; object-fit: cover;">
                            <?php else: ?>
                                <span class="text-muted small">Tidak ada gambar</span>
                            <?php endif; ?>
                        </td>
                        
                        <td>
                            <div class="fw-semibold"><?php echo e($product->nama_barang); ?></div>
                            <?php if($product->isLowStock()): ?>
                                <span class="badge text-bg-danger">Stok Menipis</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($product->category->name); ?></td>
                        <td><?php echo e($product->stok); ?></td>
                        <td><?php echo e($product->lokasi_penyimpanan ?? '-'); ?></td>
                        <td class="text-capitalize"><?php echo e(str_replace('_', ' ', $product->kondisi_barang)); ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('products.show', $product)); ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-eye"></i></a>
                            <?php if(auth()->user()->hasRole('admin', 'staff')): ?>
                                <a href="<?php echo e(route('products.edit', $product)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                                <form action="<?php echo e(route('products.destroy', $product)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus barang ini?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="8" class="text-center text-secondary py-4">Tidak ada data barang.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3">
        <?php echo e($products->links()); ?>

    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/products/index.blade.php ENDPATH**/ ?>