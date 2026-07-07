<?php $__env->startSection('title', 'Tambah Barang'); ?>
<?php $__env->startSection('page-title', 'Tambah Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:720px;">
    <form method="POST" action="<?php echo e(route('products.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>
        <?php echo $__env->make('products._form', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        <button class="btn btn-primary mt-3">Simpan Barang</button>
        <a href="<?php echo e(route('products.index')); ?>" class="btn btn-outline-secondary mt-3">Batal</a>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/products/create.blade.php ENDPATH**/ ?>