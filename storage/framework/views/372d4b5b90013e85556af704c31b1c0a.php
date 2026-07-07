<?php $__env->startSection('title', 'Masuk'); ?>

<?php $__env->startSection('content'); ?>
<section class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="auth-panel p-4 p-md-5 shadow-sm">
                <h3 class="fw-bold mb-1">Selamat Datang Kembali</h3>
                <p class="text-secondary mb-4">Masuk untuk mengakses dashboard inventaris.</p>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div><?php echo e($error); ?></div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
<?php if(session('success')): ?>
    <div class="alert alert-success">
        <?php echo e(session('success')); ?>

    </div>
<?php endif; ?>
                <form method="POST" action="<?php echo e(route('login')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required autofocus>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember">
                            <label class="form-check-label small" for="remember">Ingat saya</label>
                        </div>
                        <a href="<?php echo e(route('password.request')); ?>" class="small">Lupa password?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Masuk</button>
                </form>

                <p class="text-center small text-secondary mt-4 mb-0">
                    Belum punya akun? <a href="<?php echo e(route('register')); ?>">Daftar di sini</a>
                </p>

                <hr>
                <p class="text-center small text-secondary mb-0">
                    Akun percobaan: <code>admin@inventaris.test</code> / <code>staff@inventaris.test</code> / <code>manager@inventaris.test</code><br>
                    Password: <code>password</code>
                </p>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/auth/login.blade.php ENDPATH**/ ?>