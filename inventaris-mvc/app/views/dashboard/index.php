<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= e($title) ?> - <?= e(APP_NAME) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f8; padding: 40px; }
        .card { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); max-width: 800px; margin: auto; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background-color: #2c3e50; color: white; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .badge { background: #27ae60; color: white; padding: 4px 8px; border-radius: 4px; font-size: 12px; }
    </style>
</head>
<body>
    <div class="card">
        <h2><?= e($title) ?></h2>
        <p><span class="badge">Koneksi Database PDO Berhasil!</span></p>

        <table>
            <thead>
                <tr>
                    <th>Kode</th>
                    <th>Nama Barang</th>
                    <th>Stok</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($barang as $row): ?>
                    <tr>
                        <td><?= e($row['code']) ?></td>
                        <td><?= e($row['name']) ?></td>
                        <td><?= e($row['quantity']) ?> <?= e($row['unit']) ?></td>
                        <td>Rp <?= number_format($row['price'], 0, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>