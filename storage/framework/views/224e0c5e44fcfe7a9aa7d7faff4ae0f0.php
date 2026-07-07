<?php $__env->startSection('title', 'Ajukan Peminjaman'); ?>
<?php $__env->startSection('page-title', 'Ajukan Peminjaman Barang'); ?>

<?php $__env->startSection('content'); ?>
<div class="card p-4" style="max-width:820px;">
    <form method="POST" action="<?php echo e(route('borrowings.store')); ?>" id="borrowForm">
        <?php echo csrf_field(); ?>
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nama Peminjam</label>
                <input type="text" name="nama_peminjam" class="form-control" value="<?php echo e(old('nama_peminjam', auth()->user()->name)); ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Tanggal Pinjam</label>
                <input type="date" name="tanggal_pinjam" class="form-control" value="<?php echo e(old('tanggal_pinjam', date('Y-m-d'))); ?>" required>
            </div>
            <div class="col-md-3">
                <label class="form-label">Rencana Kembali</label>
                <input type="date" name="tanggal_kembali_rencana" class="form-control" value="<?php echo e(old('tanggal_kembali_rencana')); ?>" required>
            </div>
        </div>

        <hr class="my-4">
        <h6>Pilih Barang</h6>
        <div id="itemRows">
            <div class="row g-2 mb-1 item-row">
                <div class="col-md-8">
                    <select name="products[]" class="form-select product-select" required>
                        <option value="">-- Pilih Barang --</option>
                        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($product->id); ?>" data-stok="<?php echo e($product->stok); ?>">
                                <?php echo e($product->nama_barang); ?> (<?php echo e($product->kode_barang); ?>) &mdash; stok <?php echo e($product->stok); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="number" name="quantities[]" class="form-control qty-input" min="1" value="1" placeholder="Jumlah" required>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-outline-danger remove-row"><i class="bi bi-x"></i></button>
                </div>
            </div>
            <div class="row"><div class="col-md-8"><div class="form-text text-danger stock-warning d-none"></div></div></div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-primary" id="addRow"><i class="bi bi-plus-lg me-1"></i>Tambah Barang</button>

        <div class="mt-3">
            <label class="form-label">Catatan</label>
            <textarea name="catatan" class="form-control"><?php echo e(old('catatan')); ?></textarea>
        </div>

        <button class="btn btn-primary mt-4" id="submitBtn">Ajukan Peminjaman</button>
        <a href="<?php echo e(route('borrowings.index')); ?>" class="btn btn-outline-secondary mt-4">Batal</a>
    </form>
</div>

<script>
function checkRow(row) {
    const select = row.querySelector('.product-select');
    const qtyInput = row.querySelector('.qty-input');
    const warningEl = row.parentElement.querySelector('.stock-warning');
    const stok = parseInt(select.selectedOptions[0]?.dataset.stok ?? 0, 10);
    const qty = parseInt(qtyInput.value || '0', 10);

    qtyInput.max = stok || '';

    if (select.value && qty > stok) {
        qtyInput.classList.add('is-invalid');
        warningEl.textContent = `Jumlah melebihi stok tersedia (stok saat ini: ${stok}).`;
        warningEl.classList.remove('d-none');
        return false;
    }

    qtyInput.classList.remove('is-invalid');
    warningEl.classList.add('d-none');
    return true;
}

function checkAllRows() {
    const rows = document.querySelectorAll('.item-row');
    let allValid = true;
    rows.forEach(row => { if (!checkRow(row)) allValid = false; });
    document.getElementById('submitBtn').disabled = !allValid;
}

document.getElementById('itemRows').addEventListener('input', checkAllRows);
document.getElementById('itemRows').addEventListener('change', checkAllRows);

document.getElementById('addRow').addEventListener('click', function () {
    const rows = document.getElementById('itemRows');
    const clone = rows.querySelector('.item-row').cloneNode(true);
    clone.querySelectorAll('select, input').forEach(el => { if (el.tagName === 'SELECT') el.value = ''; else el.value = 1; });
    clone.classList.remove('is-invalid');
    rows.appendChild(clone);
    const warningRow = document.createElement('div');
    warningRow.className = 'row';
    warningRow.innerHTML = '<div class="col-md-8"><div class="form-text text-danger stock-warning d-none"></div></div>';
    rows.appendChild(warningRow);
    checkAllRows();
});
document.getElementById('itemRows').addEventListener('click', function (e) {
    if (e.target.closest('.remove-row')) {
        const rows = document.querySelectorAll('.item-row');
        if (rows.length > 1) {
            const row = e.target.closest('.item-row');
            const warningRow = row.nextElementSibling;
            row.remove();
            if (warningRow) warningRow.remove();
            checkAllRows();
        }
    }
});
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\PROJECT\inventaris-app\resources\views/borrowings/create.blade.php ENDPATH**/ ?>