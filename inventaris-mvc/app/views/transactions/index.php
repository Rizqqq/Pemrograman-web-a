<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Riwayat Transaksi Stok</h2>
    <a href="<?= base_url('items') ?>" class="btn btn-outline-primary">Kelola Stok di Data Barang &rarr;</a>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0 align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Waktu</th>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th class="text-center">Tipe</th>
                        <th class="text-center">Jumlah</th>
                        <th>Petugas</th>
                        <th>Catatan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($transactions)): ?>
                        <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada riwayat transaksi.</td></tr>
                    <?php else: ?>
                        <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><small class="text-muted"><?= date('d/m/Y H:i', strtotime($t['created_at'])) ?></small></td>
                                <td><code><?= e($t['item_code']) ?></code></td>
                                <td><strong><?= e($t['item_name']) ?></strong></td>
                                <td class="text-center">
                                    <?php if ($t['type'] === 'IN'): ?>
                                        <span class="badge bg-success">MASUK (+ IN)</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">KELUAR (- OUT)</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center fw-bold">
                                    <?= ($t['type'] === 'IN' ? '+' : '-') . $t['quantity'] ?>
                                </td>
                                <td><?= e($t['user_name'] ?? 'Sistem') ?></td>
                                <td><small><?= e($t['notes'] ?? '-') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>