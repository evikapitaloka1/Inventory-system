

<?php $__env->startSection('title', 'Manajemen User'); ?>
<?php $__env->startSection('page-title', 'Manajemen User & Role'); ?>

<?php $__env->startSection('content'); ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <form method="GET" class="d-flex gap-2 flex-wrap">
        <input type="text" name="q" value="<?php echo e(request('q')); ?>" class="form-control" placeholder="Cari nama atau email..." style="min-width:240px;">
        <select name="role_id" class="form-select" style="min-width:180px;">
            <option value="">Semua Role</option>
            <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($role->id); ?>" <?php if(request('role_id') == $role->id): echo 'selected'; endif; ?>><?php echo e($role->label ?? $role->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
        <button class="btn btn-outline-primary"><i class="bi bi-search"></i></button>
    </form>

    <a href="<?php echo e(route('users.create')); ?>" class="btn btn-primary">
        <i class="bi bi-person-plus-fill me-1"></i> Tambah User
    </a>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Terdaftar</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php echo e($user->name); ?>

                            <?php if($user->id === auth()->id()): ?>
                                <span class="badge text-bg-secondary">Anda</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo e($user->email); ?></td>
                        <td>
                            <span class="badge-soft rounded-pill px-2 py-1 text-capitalize">
                                <?php echo e($user->role->label ?? $user->role->name ?? '-'); ?>

                            </span>
                        </td>
                        <td><?php echo e($user->created_at->translatedFormat('d M Y')); ?></td>
                        <td class="text-end">
                            <a href="<?php echo e(route('users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
                            <?php if($user->id !== auth()->id()): ?>
                                <form action="<?php echo e(route('users.destroy', $user)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Hapus pengguna <?php echo e($user->name); ?>?')">
                                    <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" class="text-center text-secondary py-4">Belum ada pengguna.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="p-3"><?php echo e($users->links()); ?></div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/users/index.blade.php ENDPATH**/ ?>