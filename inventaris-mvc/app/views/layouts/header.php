<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= isset($title) ? e($title) . ' - ' : '' ?><?= APP_NAME ?></title>
    <!-- Bootstrap 5 & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { min-height: 100vh; background-color: #f8f9fa; }
        .sidebar { min-height: 100vh; background-color: #1e293b; display: flex; flex-direction: column; }
        .sidebar .nav-link { color: #94a3b8; font-weight: 500; border-radius: 8px; margin-bottom: 4px; padding: 10px 14px; }
        .sidebar .nav-link:hover { color: #ffffff; background-color: rgba(255, 255, 255, 0.1); }
        .sidebar .nav-link.active { color: #ffffff; background-color: #0d6efd; }
    </style>
</head>
<body>

<?php if ($loggedIn): ?>
<div class="container-fluid">
    <div class="row">
        <!-- SIDEBAR KIRI -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar p-3 text-white">
            <a href="<?= BASEURL ?>" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                <i class="bi bi-box-seam fs-4 me-2 text-primary"></i>
                <span class="fs-5 fw-bold"><?= APP_NAME ?></span>
            </a>
            <hr class="text-secondary">
            
            <!-- Menu Navigasi Dinamis -->
            <ul class="nav nav-pills flex-column mb-auto">
                <?php foreach ($navItems as $key => $nav): ?>
                    <li class="nav-item">
                        <a href="<?= $nav['url'] === '' ? BASEURL : BASEURL . '/' . $nav['url'] ?>" 
                           class="nav-link <?= $current === $key ? 'active' : '' ?>">
                            <i class="bi bi-<?= $nav['icon'] ?> me-2"></i> <?= $nav['label'] ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>

            <hr class="text-secondary">

            <!-- Profil User & Tombol Logout di Bawah Sidebar -->
            <div class="dropdown">
                <div class="d-flex align-items-center mb-2">
                    <i class="bi bi-person-circle fs-3 me-2 text-info"></i>
                    <div>
                        <div class="fw-bold small"><?= e($user['full_name'] ?? $user['username']) ?></div>
                        <span class="badge bg-primary text-uppercase" style="font-size: 0.65rem;"><?= e($user['role']) ?></span>
                    </div>
                </div>
                <a href="<?= BASEURL ?>/auth/logout" class="btn btn-outline-danger btn-sm w-100">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </nav>

        <!-- KONTEN UTAMA SEBELAH KANAN -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
<?php else: ?>
    <!-- Konten tanpa sidebar (khusus halaman login) -->
    <main class="container py-4">
<?php endif; ?>

        <?php /* 
KOMENTAR: Alert Bootstrap dimatikan karena sudah digantikan oleh SweetAlert2 (Swal.fire)
if ($flash): ?>
<div class="alert alert-<?= e($flash['type']) ?> alert-dismissible fade show shadow-sm <?= $loggedIn ? '' : 'mt-4' ?>">
    <?= e($flash['message']) ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; 
*/ ?>