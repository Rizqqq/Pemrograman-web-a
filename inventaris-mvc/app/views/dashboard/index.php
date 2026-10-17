<?php require ROOT_PATH . '/app/views/layouts/header.php'; ?>

<div class="p-5 mb-4 bg-white rounded-3 shadow-sm border">
  <div class="container-fluid py-3">
    <h1 class="display-6 fw-bold">Selamat Datang di <?= APP_NAME ?></h1>
    <p class="col-md-8 fs-5 text-muted">
      Sistem manajemen inventaris barang berbasis arsitektur PHP MVC murni.
    </p>
    <hr class="my-4">
    <p>Silakan gunakan menu navigasi di atas untuk mengelola data barang, kategori, dan transaksi stok.</p>
  </div>
</div>

<?php require ROOT_PATH . '/app/views/layouts/footer.php'; ?>