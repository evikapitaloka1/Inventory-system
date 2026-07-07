

<?php $__env->startSection('title', 'Tambah User'); ?>
<?php $__env->startSection('page-title', 'Tambah User Baru'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:600px;">
    <form method="POST" action="<?php echo e(route('users.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Role</label>
            <select name="role_id" class="form-select" required>
                <option value="">-- Pilih Role --</option>
                <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($role->id); ?>" <?php if(old('role_id') == $role->id): echo 'selected'; endif; ?>><?php echo e($role->label ?? $role->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-primary mt-2">Simpan</button>
        <a href="<?php echo e(route('users.index')); ?>" class="btn btn-outline-secondary mt-2">Batal</a>
    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/users/create.blade.php ENDPATH**/ ?>