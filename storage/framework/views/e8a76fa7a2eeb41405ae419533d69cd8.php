<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; color: #1e293b; }
        h2 { color: #7C3AED; margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #cbd5e1; padding: 6px 8px; text-align: left; }
        th { background-color: #f1f5f9; }
        .footer { margin-top: 20px; font-size: 10px; color: #64748b; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Barang</h2>
    <p>Dicetak pada: <?php echo e(now()->translatedFormat('d F Y H:i')); ?> WIB</p>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Barang</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $borrowings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $borrowing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($borrowing->kode_peminjaman); ?></td>
                    <td><?php echo e($borrowing->nama_peminjam); ?></td>
                    <td>
                        <?php $__currentLoopData = $borrowing->details; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $detail): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo e($detail->product->nama_barang); ?> &times;<?php echo e($detail->jumlah); ?><?php if(!$loop->last): ?>, <?php endif; ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </td>
                    <td><?php echo e(optional($borrowing->tanggal_pinjam)->format('d-m-Y')); ?></td>
                    <td><?php echo e(optional($borrowing->tanggal_kembali_rencana)->format('d-m-Y')); ?></td>
                    <td><?php echo e(optional($borrowing->tanggal_kembali)->format('d-m-Y') ?? '-'); ?></td>
                    <td><?php echo e($borrowing->statusLabel()); ?></td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>

    <p class="footer">Sistem Manajemen Inventaris &mdash; Challenge Seleksi Magang Sistem Informasi</p>
</body>
</html>
<?php /**PATH C:\PROJECT\inventaris-app\resources\views/reports/borrowings-pdf.blade.php ENDPATH**/ ?>