<?php $__env->startSection('title', 'Lupa Password'); ?>

<?php $__env->startSection('content'); ?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-panel p-4 p-md-5 shadow-sm">
                <h3 class="fw-bold mb-1">Lupa Password?</h3>
                <p class="text-secondary mb-4">Masukkan email akun Anda. Jika terdaftar, Anda akan langsung diarahkan ke halaman untuk mengatur password baru.</p>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?php echo e(route('password.email')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Lanjutkan ke Reset Password</button>
                </form>

                <p class="text-center small text-secondary mt-4 mb-0">
                    <a href="<?php echo e(route('login')); ?>">&larr; Kembali ke halaman masuk</a>
                </p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/auth/forgot-password.blade.php ENDPATH**/ ?>