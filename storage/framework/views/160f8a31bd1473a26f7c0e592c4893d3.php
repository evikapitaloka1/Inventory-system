<?php $product = $product ?? null; ?>

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Kode Barang</label>
        <input type="text" name="kode_barang" class="form-control" value="<?php echo e(old('kode_barang', $product->kode_barang ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Nama Barang</label>
        <input type="text" name="nama_barang" class="form-control" value="<?php echo e(old('nama_barang', $product->nama_barang ?? '')); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Kategori</label>
        <select name="category_id" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php if(old('category_id', $product->category_id ?? '') == $category->id): echo 'selected'; endif; ?>>
                    <?php echo e($category->name); ?>

                </option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Stok</label>
        <input type="number" min="0" name="stok" class="form-control" value="<?php echo e(old('stok', $product->stok ?? 0)); ?>" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Stok Minimum</label>
        <input type="number" min="0" name="stok_minimum" class="form-control" value="<?php echo e(old('stok_minimum', $product->stok_minimum ?? 5)); ?>" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Lokasi Penyimpanan</label>
        <input type="text" name="lokasi_penyimpanan" class="form-control" value="<?php echo e(old('lokasi_penyimpanan', $product->lokasi_penyimpanan ?? '')); ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Kondisi Barang</label>
        <select name="kondisi_barang" class="form-select" required>
            <?php $__currentLoopData = ['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($val); ?>" <?php if(old('kondisi_barang', $product->kondisi_barang ?? 'baik') == $val): echo 'selected'; endif; ?>><?php echo e($label); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Gambar Barang</label>
        <input type="file" name="gambar" class="form-control" accept="image/*">
        <?php if(!empty($product?->gambar)): ?>
            <img src="<?php echo e($product->imageUrl()); ?>" class="mt-2 rounded" style="height:80px;">
        <?php endif; ?>
    </div>
    <div class="col-12">
        <label class="form-label">Deskripsi</label>
        <textarea name="deskripsi" class="form-control" rows="3"><?php echo e(old('deskripsi', $product->deskripsi ?? '')); ?></textarea>
    </div>
</div>
<?php /**PATH C:\PROJECT\inventaris-app\resources\views/products/_form.blade.php ENDPATH**/ ?>